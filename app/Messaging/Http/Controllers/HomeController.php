<?php

namespace App\Messaging\Http\Controllers;

use Illuminate\Http\Request;

class HomeController
{
    public function index(Request $request)
    {
        return response()->json('welcome to the club');
    }
}
