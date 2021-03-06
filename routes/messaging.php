<?php

Route::middleware('auth:api')->group(function () {
    Route::apiResource('messages', 'MessagesController');
    Route::apiResource('conversations', 'ConversationController', [
        'index',
        'show',
        'destroy',
    ]);
});
