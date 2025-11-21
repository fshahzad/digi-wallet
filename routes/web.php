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

/*
Route::post('/broadcasting/auth2', function (Illuminate\Http\Request $request) {
    logger()->debug('Broadcasting auth attempt', [
        'user' => auth()->user(),
        'channel' => $request->channel_name,
        'authenticated' => auth()->check()
    ]);

    // Continue with normal auth
    return Broadcast::auth($request);
});
*/
