<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

Broadcast::channel('User.{id}', function (User $user, $id) {
    return (int) $user->id === (int) User::findOrFail($id)?->id ?? 0;
});
