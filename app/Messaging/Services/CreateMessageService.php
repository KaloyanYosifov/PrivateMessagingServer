<?php

namespace App\Messaging\Services;

use App\Messaging\Events\MessageCreatedEvent;
use App\Messaging\Builders\MessageBuilder;

class CreateMessageService
{
    public function createMessage(MessageBuilder $builder)
    {
        return tap($builder->build(), function ($message) {
            event(new MessageCreatedEvent($message));
        });
    }
}
