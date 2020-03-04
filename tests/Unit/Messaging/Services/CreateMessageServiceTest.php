<?php

namespace Tests\Unit\Messaging\Services;

use App\User;
use Tests\TestCase;
use App\Messaging\Events\MessageCreatedEvent;
use Illuminate\Support\Facades\Event;
use App\Messaging\Builders\MessageBuilder;
use App\Messaging\Services\CreateMessageService;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateMessageServiceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_creates_a_message_and_emits_an_event()
    {
        Event::fake(MessageCreatedEvent::class);

        $fromUser = factory(User::class)->create();
        $toUser = factory(User::class)->create();
        $text = 'Some text';
        $messageBuilder = app()->make(MessageBuilder::class);
        $messageBuilder = $messageBuilder->setFromUser($fromUser)->setToUser($toUser)->setText($text);
        $message = app()->make(CreateMessageService::class)->createMessage($messageBuilder);

        Event::assertDispatched(MessageCreatedEvent::class, function (MessageCreatedEvent $event) use ($message) {
            return $event->message === $message;
        });
    }
}
