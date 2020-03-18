<?php

Route::middleware('auth:api')->group(function () {
    Route::post('messages/audio-upload', 'MessagesController@audioUpload');

    Route::apiResource('messages', 'MessagesController');
    Route::apiResource('conversations', 'ConversationController', [
        'index',
        'show',
        'destroy',
    ]);
});
