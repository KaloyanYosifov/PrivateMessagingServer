<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use App\Messaging\Models\Message;
use App\Messaging\Models\Conversation;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;
    use WithFaker;

    /** @test */
    public function it_has_sent_messages_from_conversations()
    {
        $conversation = factory(Conversation::class)->create();
        $secondConversation = factory(Conversation::class)->create();
        $user = factory(User::class)->create();
        $firstConversationSentMessagesId = [];
        $secondConversationSentMessagesId = [];

        $this->assertEquals(0, $user->sentMessages($conversation->id)->count());

        $firstConversationSentMessagesId[] = $this->createMessage($conversation->id, $user->id)->id;
        $firstConversationSentMessagesId[] = $this->createMessage($conversation->id, $user->id)->id;
        $secondConversationSentMessagesId[] = $this->createMessage($secondConversation->id, $user->id)->id;

        $user->fresh();

        $firstConversationSentMessagesQuery = $user->sentMessages($conversation->id);
        $secondConversationSentMessagesQuery = $user->sentMessages($secondConversation->id);

        $this->assertEquals(2, $firstConversationSentMessagesQuery->count());
        $this->assertEquals(1, $secondConversationSentMessagesQuery->count());

        $firstConversationSentMessages = $firstConversationSentMessagesQuery->get();
        $secondConversationSentMessages = $secondConversationSentMessagesQuery->get();

        foreach ($firstConversationSentMessages as $message) {
            $this->assertTrue(in_array($message->id, $firstConversationSentMessagesId));
        }

        foreach ($secondConversationSentMessages as $message) {
            $this->assertTrue(in_array($message->id, $secondConversationSentMessagesId));
        }
    }

    /** @test */
    public function it_has_conversations()
    {
        $user = factory(User::class)->create();

        $this->assertEquals(0, $user->conversations()->count());

        $user->conversations()->create();

        $user->fresh();

        $this->assertEquals(1, $user->conversations()->count());
    }

    protected function createMessage(int $conversationId, int $userId): Message
    {
        $message = new Message();
        $message->conversation_id = $conversationId;
        $message->user_id = $userId;
        $message->text = $this->faker->text;
        $message->save();

        return $message;
    }
}
