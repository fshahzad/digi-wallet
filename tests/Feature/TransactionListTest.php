<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class TransactionListTest extends TestCase
{

    use RefreshDatabase;

    public function test_user_can_fetch_transaction_history()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->getJson('/api/transactions');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'balance',
            'transactions'
        ]);
    }

}
