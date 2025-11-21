<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Transaction;
use App\Events\TransferEvent;
use Illuminate\Support\Facades\Event;

class RealtimeTransferEventTest extends TestCase
{
    use RefreshDatabase;

    public function test_transfer_event_is_broadcast()
    {
        Event::fake();

        $txn = Transaction::factory()->create();
        event(new TransferEvent($txn));

        Event::assertDispatched(TransferEvent::class);
    }
}
