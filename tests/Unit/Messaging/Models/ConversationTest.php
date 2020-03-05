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

        $newConversation = Conversation::findOrCreate($user, $user2);

        $this->assertTrue($conversation->is($newConversation));
    }

    /** @test */
    public function it_can_load_all_of_its_users()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $conversation = factory(Conversation::class)->create();

        $conversation->users()->attach([$user->id, $user2->id]);

        $freshConversation = Conversation::find($conversation->id);

        $this->assertArrayNotHasKey('users', $freshConversation->toArray());

        $freshConversation->loadUsers();

        $this->assertArrayHasKey('users', $freshConversation->toArray());
        $this->assertCount(2, $freshConversation->users);
    }

    /** @test */
    public function it_can_load_all_of_its_users_and_can_choose_to_not_load_some()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $conversation = factory(Conversation::class)->create();

        $conversation->users()->attach([$user->id, $user2->id]);

        $freshConversation = Conversation::find($conversation->id);

        $freshConversation->loadUsers([$user->id]);

        $this->assertCount(1, $freshConversation->users);
        $this->assertTrue($freshConversation->users[0]->is($user2));
    }
}
