<?php

namespace Tests\Unit\Passport\Repositories;

use Laravel\Passport\Client;
use App\Passport\Repositories\ClientRepository;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ClientRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_gets_password_and_personal_client()
    {
        $this->assertEquals(0, Client::count());

        $passwordClient = factory(Client::class)->state('password')->create(['revoked' => false]);
        $personalAccessClient = factory(Client::class)->state('personal')->create(['revoked' => false]);

        $this->assertEquals(2, Client::count());

        /**
         * @var ClientRepository $repository
         */
        $repository = app()->make(ClientRepository::class);

        $this->assertTrue($passwordClient->is($repository->getPasswordClient()));
        $this->assertTrue($personalAccessClient->is($repository->getPersonalAccessClient()));
    }

    /** @test */
    public function it_returns_null_if_client_is_revoked()
    {
        factory(Client::class)->state('password')->create(['revoked' => true]);

        /**
         * @var ClientRepository $repository
         */
        $repository = app()->make(ClientRepository::class);

        $this->assertNull($repository->getPasswordClient());
    }
}
