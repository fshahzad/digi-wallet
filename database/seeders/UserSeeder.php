<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    public function run() {
        User::factory()->create([
            'name' => 'Alice',
            'email' => 'alice@localhost.com',
            'password' => Hash::make('password'),
            'balance' => 10000.00
        ]);
        User::factory()->create([
            'name' => 'Bob',
            'email' => 'bob@localhost.com',
            'password' => Hash::make('password'),
            'balance' => 100.00
        ]);
        User::factory()->create([
            'name' => 'Charlie',
            'email' => 'charlie@localhost.com',
            'password' => Hash::make('password'),
            'balance' => 500.00
        ]);
        User::factory()->create([
            'name' => 'David',
            'email' => 'david@localhost.com',
            'password' => Hash::make('password'),
            'balance' => 2500.00
        ]);
    }
}
