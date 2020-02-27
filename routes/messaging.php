<?php

Route::middleware('auth:api')->group(function () {
    Route::resource('messages', 'MessagesController');
});
