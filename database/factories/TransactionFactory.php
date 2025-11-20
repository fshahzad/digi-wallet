<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Transaction;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $amount = $this->faker->randomFloat(2, 1, 100);
        $sender = \App\Models\User::factory()->create([
            'balance' => 1000,
        ]);
        $receiver = \App\Models\User::factory()->create([
            'balance' => 500,
        ]);
        return [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'trans_type' => $this->faker->randomElement([Transaction::TYPE_SENT, Transaction::TYPE_RECEIVED]),
            'amount' => $amount,
            'commission_fee' => $amount * 0.015,
            'sender_balance_before' => $sender->balance,
            'sender_balance_after' => $sender->balance - $amount - ($amount * 0.015),
            'receiver_balance_before' => $receiver->balance,
            'receiver_balance_after' => $receiver->balance + $amount,
            'status' => 'completed',
            'extra' => ['note' => $this->faker->sentence(), 'reference' => $this->faker->uuid()],
        ];
    }
}
