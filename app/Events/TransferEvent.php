<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;
use Illuminate\Queue\SerializesModels;

class TransferEvent implements ShouldBroadcast, ShouldDispatchAfterCommit
{
    use InteractsWithSockets, SerializesModels;

    public function __construct(public Transaction $transaction)
    {
        //Eager load the transaction with relations
        $this->transaction = $transaction->load(['sender', 'receiver']);
    }

    /**
     * broadcast to private channels for sender/receiver users
     */
    public function broadcastOn()
    {
        return [
            new PrivateChannel('user.'.$this->transaction->sender_id),
            new PrivateChannel('user.'.$this->transaction->receiver_id),
        ];
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->transaction->id,
            'receiver' => [
                'id' => $this->transaction->receiver->id,
                'name' => $this->transaction->receiver->name,
            ],
            'sender' => [
                'id' => $this->transaction->sender->id,
                'name' => $this->transaction->sender->name,
            ],
            'sender_id' => $this->transaction->sender_id,
            'receiver_id' => $this->transaction->receiver_id,
            'amount' => $this->transaction->amount,
            'commission' => $this->transaction->commission, //to be or not be sent ??
            //'sender_balance_after' => $this->transaction->sender_balance_after,
            'receiver_balance_before' => $this->transaction->receiver_balance_before,
            'receiver_balance_after' => $this->transaction->receiver_balance_after,
            'trans_date' => $this->transaction->created_at->format('d F, Y H:i:s'),
        ];
    }

    public function broadcastAs()
    {
        return 'WalletTransfer';
        /*
        return match($this->transaction->trans_type) {
            Transaction::TYPE_SENT => 'TransferSent',
            Transaction::TYPE_RECEIVED => 'TransferReceived',
        };
        */
    }
}
