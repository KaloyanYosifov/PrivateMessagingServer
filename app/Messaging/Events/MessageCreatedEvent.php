<?php

namespace App\Events;

use App\Messaging\Models\Message;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class MessageCreatedEvent
{
    use Dispatchable, SerializesModels;

    /**
     * @var Message
     */
    public $message;

    /**
     * Create a new event instance.
     *
     * @param Message $message
     */
    public function __construct(Message $message)
    {
        //
        $this->message = $message;
    }
}
