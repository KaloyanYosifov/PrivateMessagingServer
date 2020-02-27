<?php

namespace App\Messaging\Http\Controllers;

use App\Messaging\Models\Message;
use App\Messaging\Services\CreateMessageService;
use App\Messaginng\Http\Requests\CreateMessageRequest;

class MessagesController
{
    public function index()
    {
        return response()->json(Message::paginate(15));
    }

    public function store(CreateMessageRequest $request, CreateMessageService $createMessageService)
    {
        return response()->json(
            $createMessageService->createMessage($request->getBuilder())
        );
    }
}
