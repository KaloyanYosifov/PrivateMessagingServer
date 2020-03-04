<?php

namespace App\Messaging\Builders;

use App\User;
use App\Messaging\Models\Message;
use App\Messaging\Models\Conversation;

class MessageBuilder
{
    /**
     * @var User
     */
    protected $fromUser;

    /**
     * @var User
     */
    protected $toUser;

    /**
     * @var string
     */
    protected $text;

    /**
     * @var Conversation|null
     */
    protected $conversation;

    public function setFromUser(User $fromUser): self
    {
        $this->fromUser = $fromUser;

        return $this;
    }

    public function setToUser(User $toUser): self
    {
        $this->toUser = $toUser;

        return $this;
    }

    public function setConversation(Conversation $conversation)
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function build(): Message
    {
        $this->checkIfWeHaveRequiredFields();

        $conversation = $this->conversation ?? Conversation::findOrCreate($this->fromUser, $this->toUser);

        return Message::forceCreate([
            'user_id' => $this->fromUser->id,
            'conversation_id' => $conversation->id,
            'text' => $this->text,
        ]);
    }

    protected function checkIfWeHaveRequiredFields()
    {
        $fields = [
            'fromUser',
            'text',
        ];

        foreach ($fields as $field) {
            if (!$this->{$field}) {
                throw new \InvalidArgumentException("The field $field is required!");
            }
        }

        if (!$this->toUser && !$this->conversation) {
            throw new \InvalidArgumentException("You need to specify at least a conversation or a toUser!");
        }
    }
}
