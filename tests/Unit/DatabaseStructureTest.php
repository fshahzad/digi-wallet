<?php

namespace Tests\Unit;

//use PHPUnit\Framework\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseStructureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Summary of test_users_table_has_balance_column
     * @return void
     */
    public function test_users_table_has_balance_column()
    {
        $this->assertTrue(
            Schema::hasColumn('users', 'balance')
        );
    }


    /**
     * Summary of test_transactions_table_exists
     * @return void
     */
    public function test_transactions_table_exists()
    {
        $this->assertTrue(Schema::hasTable('transactions'));
    }
}
