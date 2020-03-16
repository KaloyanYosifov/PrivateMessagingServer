<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Passport\Repositories\ClientRepository;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request, ClientRepository $clientRepository)
    {
        $user = factory(User::class)
            ->create(
                $request->only('name', 'email', 'password')
            );

        $passwordClient = $clientRepository->getPasswordClient();

        if (!$passwordClient) {
            return $user;
        }

        $request->replace([
            'grant_type' => 'password',
            'username' => $request->email,
            'password' => $request->password,
            'client_id' => $passwordClient->id,
            'client_secret' => $passwordClient->secret,
            'scope' => '',
        ]);

        $proxy = Request::create(
            'oauth/token',
            'POST'
        );

        return \Route::dispatch($proxy);
    }
}
