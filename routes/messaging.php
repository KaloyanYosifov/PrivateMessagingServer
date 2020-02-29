<?php

Route::middleware('auth:api')->group(function () {
    Route::apiResource('messages', 'MessagesController');
});
