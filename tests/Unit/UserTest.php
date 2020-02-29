<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use App\Messaging\Models\Message;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_has_sent_and_received_messages()
    {
        $MESSAGES_TO_CREATE = 3;
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $messages = factory(Message::class, $MESSAGES_TO_CREATE)->create([
            'from_user_id' => $user,
            'to_user_id' => $user2,
        ]);

        $this->assertTrue($user2->sentMessages->isEmpty());
        $this->assertTrue($user->receivedMessages->isEmpty());

        $this->assertFalse($user->sentMessages->isEmpty());
        $this->assertFalse($user2->receivedMessages->isEmpty());

        foreach ($messages as $message) {
            $this->assertTrue($user->sentMessages->contains('id', $message->id));
        }

        foreach ($messages as $message) {
            $this->assertTrue($user2->receivedMessages->contains('id', $message->id));
        }
    }
}
