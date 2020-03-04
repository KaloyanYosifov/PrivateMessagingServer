<?php

namespace App\Messaging\Exceptions;

class UserNotInConversationException extends \Exception
{
    public function __construct()
    {
        parent::__construct('User is not in conversation!');
    }
}
