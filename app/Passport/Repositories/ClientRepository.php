<?php

namespace App\Passport\Repositories;

use Laravel\Passport\Client;

class ClientRepository
{
    public function getPersonalAccessClient(): ?Client
    {
        return Client::where('personal_access_client', true)->where('revoked', false)->first();
    }

    public function getPasswordClient(): ?Client
    {
        return Client::where('password_client', true)->where('revoked', false)->first();
    }
}
