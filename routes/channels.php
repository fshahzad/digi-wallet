<?php

use Illuminate\Support\Facades\Broadcast;
use App\Models\User;

Broadcast::channel('user.{id}', function (User $user, $id) {
    //logger()->debug('Broadcasting to User channel:', ['user_id' => $user->id, 'channel_id' => $id]);
    //return intval($user->id) === intval($id);
    return (int) $user->id === (int) User::findOrFail($id)?->id ?? 0;
});
