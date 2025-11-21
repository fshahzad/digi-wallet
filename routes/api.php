<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('current-user', function (Request $request) {
        return response()->json(['user' => $request->user()->toArray()]);
    });

    Route::get('transactions', [TransactionController::class, 'index']);
    Route::post('transactions', [TransactionController::class, 'store']);
});
