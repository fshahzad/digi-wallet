<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id')->references('id')->on('users')->index();
            $table->unsignedBigInteger('receiver_id')->references('id')->on('users')->index();
            $table->decimal('amount', 20, 2);
            $table->decimal('commission_fee', 20, 2)->default(0);
            $table->decimal('sender_balance_before', 20,2)->nullable();
            $table->decimal('sender_balance_after', 20,2)->nullable();
            $table->decimal('receiver_balance_before', 20,2)->nullable();
            $table->decimal('receiver_balance_after', 20,2)->nullable();
            $table->string('status')->default('completed'); // retry/pending/failed etc.
            $table->json('extra')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
