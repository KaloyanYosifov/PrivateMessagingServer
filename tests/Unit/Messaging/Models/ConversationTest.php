<?php

namespace Tests\Unit\Messaging\Models;

use App\User;
use App\Messaging\Models\Conversation;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ConversationTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_create_conversation_between_users()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this->assertTrue($user->conversations->isEmpty());
        $this->assertTrue($user2->conversations->isEmpty());

        $conversation = Conversation::findOrCreate($user, $user2);

        $user->refresh();
        $user2->refresh();

        $this->assertFalse($user->conversations->isEmpty());
        $this->assertFalse($user2->conversations->isEmpty());
        $this->assertTrue($user->conversations[0]->is($conversation));
        $this->assertTrue($user2->conversations[0]->is($conversation));
    }

    /** @test */
    public function it_finds_a_conversation()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $conversation = factory(Conversation::class)->create();

        $conversation->users()->attach([$user->id, $user2->id]);
        $conversation->save();

        $newConversation = Conversation::findOrCreate($user, $user2);

        $this->assertTrue($conversation->is($newConversation));
    }
}
