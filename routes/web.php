<?php

use Illuminate\Support\Facades\Route;
use App\Models\User;

Route::get('/', function (Request $request) {
    $user = User::inRandomOrder()->first();
    auth()->login($user);
    //session()->regenerate();
    //session()->save();
    return view('welcome');
});
