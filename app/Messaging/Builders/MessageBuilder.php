<?php

namespace App\Messaging\Builders;

use App\User;
use App\Messaging\Models\Message;
use App\Messaging\Models\Conversation;
use App\Messaging\Exceptions\UserNotInConversationException;

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
    protected $text = '';

    /**
     * @var string
     */
    protected $audioPath;

    /**
     * @var Conversation|null
     */
    protected $conversation;

    public function setSender(User $fromUser): self
    {
        $this->fromUser = $fromUser;

        return $this;
    }

    public function setReceiver(User $toUser): self
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

    public function setAudioPath(string $path): self
    {
        $this->audioPath = $path;

        return $this;
    }

    /**
     * @return Message
     * @throws \Throwable
     */
    public function build(): Message
    {
        $this->checkIfWeHaveRequiredFields();

        $conversation = $this->conversation ?? Conversation::findOrCreate($this->fromUser, $this->toUser);

        throw_unless($conversation->hasUser($this->fromUser), UserNotInConversationException::class);

        return Message::forceCreate([
            'user_id' => $this->fromUser->id,
            'conversation_id' => $conversation->id,
            'text' => $this->text,
            'audio_path' => $this->audioPath,
        ]);
    }

    protected function checkIfWeHaveRequiredFields()
    {
        $fields = [
            'fromUser',
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
