<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Messaging\Services\CreateMessageService;
use App\Messaging\Http\Requests\ShowMessageRequest;
use App\Messaging\Http\Requests\CreateMessageRequest;
use App\Messaging\Http\Requests\UpdateMessageRequest;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        return response()->paginate(Auth::user()->conversations(), 30);
    }

    public function show(ShowMessageRequest $request, Message $message)
    {
        return response()->json($message);
    }

    public function store(CreateMessageRequest $request, CreateMessageService $createMessageService)
    {
        return response()->json(
            $createMessageService->createMessage($request->getBuilder())
        );
    }

    public function update(UpdateMessageRequest $request, Message $message)
    {
        return response()->json(
            tap($message)->update($request->all())
        );
    }
}
