<?php

namespace App\Messaging\Services;

use App\Events\MessageCreatedEvent;
use App\Messaging\Builders\MessageBuilder;

class CreateMessageService
{
    public function createMessage(MessageBuilder $builder)
    {
        event(new MessageCreatedEvent($builder->build()));
    }
}
