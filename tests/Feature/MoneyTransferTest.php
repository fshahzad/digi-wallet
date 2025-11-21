<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class MoneyTransferTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_transfer_money()
    {
        $sender = User::factory()->create(['balance' => 200]);
        $receiver = User::factory()->create(['balance' => 0]);


        $this->actingAs($sender);


        $response = $this->postJson('/api/transactions', [
            'receiver_id' => $receiver->id,
            'amount' => 100
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('transactions', [
            'sender_id' => $sender->id,
            'receiver_id' => $receiver->id,
            'amount' => 100
        ]);
    }
}
