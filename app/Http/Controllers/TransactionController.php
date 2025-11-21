<?php

namespace App\Http\Controllers;

use App\Events\TransferEvent;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\QueryException;

class TransactionController extends Controller
{
    // Commission rate 1.5%
    // defined as property for easy adjustment but can be moved to config
    protected $commissionRate = 1.5/100;

    protected $maxAttempts = 5; // for high load we can retry in loop for deadlocks

    /**
     * Fetch user transactions with pagination
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $transactions = Transaction::select('*', DB::raw("DATE_FORMAT(created_at, '%d %M, %Y %T') AS trans_date"))
            ->where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->with(['sender:id,name', 'receiver:id,name'])
            ->orderBy('id', 'desc')
            ->paginate(5);

        return response()->json([
            'balance' => $user->balance,
            'transactions' => $transactions,
        ]);
    }

    /**
     * Store a new transaction (wallet transfer) in secure retry-able database transaction
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'receiver_id' => 'required|integer|exists:users,id|not_in:'.$request->user()->id,
            'amount' => 'required|numeric|min:1',
        ], [
            'receiver_id.exists' => 'The selected receiver does not exist.',
            'receiver_id.required' => 'Please select a receiver for the transfer.',
            'receiver_id.integer' => 'Invalid receiver selected.',
            'receiver_id.not_in' => 'You cannot transfer to yourself.',
            'amount.min' => 'The amount must be at least 1.',
            'amount.required' => 'Please enter an amount to transfer.',
            'amount.numeric' => 'The amount must be a valid number.',
        ]); //ToDo: translations for validation messages

        $senderId = $request->user()->id;
        $receiverId = intval($data['receiver_id']);
        $amount = round(floatval($data['amount']), 2);

        // commission calculation with total to debit
        $commission = $this->calculateCommission($amount);
        $debitTotal = bcadd($amount, $commission, 2);

        // implemention of retry logic for deadlocks
        $attempt = 0;

        do {
            try {
                return DB::transaction(function () use ($senderId, $receiverId, $amount, $commission, $debitTotal, $request) {
                    // lock rows in deterministic order to reduce deadlocks:
                    $sender = User::where('id', $senderId)->lockForUpdate()->first();
                    $receiver = User::where('id', $receiverId)->lockForUpdate()->first();

                    // Validate sufficient balance
                    if (bccomp($sender->balance, $debitTotal, 2) < 0) {
                        throw ValidationException::withMessages(['amount' => 'Insufficient balance.']);
                    }

                    // Record balances before
                    $senderBalanceBefore = $sender->balance;
                    $receiverBalanceBefore = $receiver->balance;

                    // Update balances with bcmath to keep precision
                    $sender->balance = bcsub($sender->balance, $debitTotal, 2);
                    $receiver->balance = bcadd($receiver->balance, $amount, 2);

                    $sender->save();
                    $receiver->save();

                    // Create transaction records
                    $txn = Transaction::create([
                        'sender_id' => $sender->id,
                        'receiver_id' => $receiver->id,
                        'amount' => $amount,
                        'trans_type' => Transaction::TYPE_SENT,
                        'commission_fee' => $commission,
                        'sender_balance_before' => $senderBalanceBefore,
                        'sender_balance_after' => $sender->balance,
                        'receiver_balance_before' => $receiverBalanceBefore,
                        'receiver_balance_after' => $receiver->balance,
                        'status' => 'completed',
                    ]);
                    // Broadcast event to sender/receiver users channels
                    broadcast(new TransferEvent($txn))->toOthers(); // we'll use private channels
                    //
                    return response()->json([
                        'message' => 'Transfer completed',
                        //'transaction' => $txn, //Depends on need to expose full transaction details or not
                        'receiver' => [
                            'id' => $receiver->id,
                            'name' => $receiver->name,
                        ],
                        'amount' => $amount,
                        'balance' => $sender->balance,
                    ], 200);
                }, $this->maxAttempts); // transaction attempts

            } catch (QueryException $ex) {
                // detect database failure or deadlock insertion failure [[ SQLSTATE using MySQL 40001 etc... ]]
                $code = $ex->getCode();
                logger()->error('Attempt # '.$attempt.' => Transaction failed: '.$ex->getMessage(), ['code' => $code]);
                $attempt++;
                if ($attempt >= $this->maxAttempts) {
                    throw $ex;
                }
                // little sleep for backoff
                usleep(100000 * $attempt);
                continue;
            } catch (ValidationException $ve) {
                throw $ve;
            }
        } while ($attempt < $this->maxAttempts);

        // Final fall back error, not an real time ideal situation :-(
        return response()->json(['message' => 'Unable to complete transfer'], 500);
    }

    /**
     * Commission rounded to 2 decimals with hight precision
     * number_format always does the lower rounding value so keep in mind
     * @param float $amount
     * @return string
     */
    protected function calculateCommission(float $amount): string
    {
        $fee = bcmul((string)$amount, (string)$this->commissionRate, 2);
        return number_format(floatval($fee), 2, '.', '');
    }
}
