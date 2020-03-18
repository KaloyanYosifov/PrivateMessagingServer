<?php

namespace Tests\Unit\Messaging\Builders;

use App\User;
use App\Messaging\Models\Conversation;
use Illuminate\Support\Facades\Storage;
use App\Messaging\Builders\MessageBuilder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Messaging\Exceptions\UserNotInConversationException;

class MessageBuilderTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_build_a_message()
    {
        $fromUser = factory(User::class)->create();
        $toUser = factory(User::class)->create();
        $text = 'Some text';
        $messageBuilder = app()->make(MessageBuilder::class);

        $message = $messageBuilder->setSender($fromUser)->setReceiver($toUser)->setText($text)->build();

        $this->assertTrue($message->user->is($fromUser));
        $this->assertEquals($text, $message->text);
    }

    /** @test */
    public function it_can_build_a_message_from_conversation()
    {
        $fromUser = factory(User::class)->create();
        /**
         * @var Conversation $conversation
         */
        $conversation = $fromUser->conversations()->create();
        $text = 'Some text';
        $messageBuilder = app()->make(MessageBuilder::class);

        $message = $messageBuilder->setSender($fromUser)->setConversation($conversation)->setText($text)->build();

        $this->assertTrue($message->user->is($fromUser));
        $this->assertEquals($message->conversation->id, $conversation->id);
        $this->assertEquals($text, $message->text);
    }

    /** @test */
    public function it_can_build_a_message_with_audio_path_only()
    {
        $fromUser = factory(User::class)->create();
        /**
         * @var Conversation $conversation
         */
        $conversation = $fromUser->conversations()->create();
        $messageBuilder = app()->make(MessageBuilder::class);
        $audioFilePath = 'messages/audio/audio-file.acc';
        $message = $messageBuilder
            ->setSender($fromUser)
            ->setConversation($conversation)
            ->setAudioPath($audioFilePath)
            ->build();

        $this->assertEquals($message->audio_url, Storage::cloud()->url($audioFilePath));
    }

    /** @test */
    public function it_throws_an_error_if_we_dont_have_one_of_the_fields()
    {
        $this->expectException(\InvalidArgumentException::class);

        $fromUser = factory(User::class)->create();
        $messageBuilder = app()->make(MessageBuilder::class);

        $messageBuilder->setSender($fromUser)->build();
    }

    /** @test */
    public function it_throw_an_error_if_we_dont_have_either_a_conversation_or_a_to_user()
    {
        $this->expectException(\InvalidArgumentException::class);

        $fromUser = factory(User::class)->create();
        $text = 'test';
        $messageBuilder = app()->make(MessageBuilder::class);

        $messageBuilder->setSender($fromUser)->setText($text)->build();
    }

    /** @test */
    public function it_doesnt_throw_an_error_if_we_have_either_to_user_or_conversation()
    {
        $fromUser = factory(User::class)->create();
        /**
         * @var Conversation $conversation
         */
        $conversation = $fromUser->conversations()->create();
        $text = 'test';
        $messageBuilder = app()->make(MessageBuilder::class);

        $messageBuilder->setSender($fromUser)->setConversation($conversation)->setText($text)->build();

        $this->assertTrue(true);
    }

    /** @test */
    public function it_throws_an_error_if_sender_is_not_in_conversation()
    {
        $this->expectException(UserNotInConversationException::class);

        $sender = factory(User::class)->create();
        $fakeSender = factory(User::class)->create();
        /**
         * @var Conversation $conversation
         */
        $conversation = $sender->conversations()->create();
        $text = 'Some text';
        $messageBuilder = app()->make(MessageBuilder::class);

        $messageBuilder->setSender($fakeSender)->setConversation($conversation)->setText($text)->build();
    }
}
