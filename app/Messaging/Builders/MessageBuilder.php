<?php

namespace App\Messaging\Builders;

use App\User;
use App\Messaging\Models\Message;

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

    public function setText(string $text): self
    {
        $this->text = $text;

        return $this;
    }

    public function build(): Message
    {
        $this->checkIfWeHaveRequiredFields();

        return Message::forceCreate([
            'from_user_id' => $this->fromUser->id,
            'to_user_id' => $this->toUser->id,
            'text' => $this->text,
        ]);
    }

    protected function checkIfWeHaveRequiredFields()
    {
        $fields = [
            'fromUser',
            'toUser',
            'text',
        ];

        foreach ($fields as $field) {
            if (!$this->{$field}) {
                throw new \InvalidArgumentException("The field $field is required!");
            }
        }
    }
}
