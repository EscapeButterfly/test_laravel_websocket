<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat-message{id}', function ($user) {
    return Auth::check();
});

Broadcast::channel('online-users', function (\App\Models\User $user) {
    if (Auth::check()) {
        return ['id' => $user->id, 'name' => $user->name];
    }
});
