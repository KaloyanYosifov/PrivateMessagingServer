<?php

Route::get('messaging/test', 'HomeController@index');

Route::middleware('auth:api')->group(function () {
});
