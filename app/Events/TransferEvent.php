<?php

namespace App\Events;

use App\Models\Transaction;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;

class TransferEvent implements ShouldBroadcast
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
            'transaction' => $this->transaction->only([
                'id',
                'sender_id',
                'receiver_id',
                'amount',
                'trans_type',
                //'commission', // Decision to include commission or not?
            ])
        ];
    }

    public function broadcastAs()
    {
        return match($this->transaction->trans_type) {
            Transaction::TYPE_SENT => 'TransferSent',
            Transaction::TYPE_RECEIVED => 'TransferReceived',
        };
    }
}
