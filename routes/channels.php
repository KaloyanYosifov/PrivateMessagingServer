<?php

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

use App\Messaging\Models\Conversation;

Broadcast::channel('conversation.message.created.{id}', function ($user, $id) {
    return Conversation::find($id)->hasUser($user);
});
