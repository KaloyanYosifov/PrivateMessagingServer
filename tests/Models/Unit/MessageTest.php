<?php

namespace Tests\Unit\Messaging\Models;

use App\Messaging\Models\Message;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MessageTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_generates_unique_id_automatically()
    {
        $message = factory(Message::class)->create();

        $this->assertNotNull($message->unique_id);
    }
}
