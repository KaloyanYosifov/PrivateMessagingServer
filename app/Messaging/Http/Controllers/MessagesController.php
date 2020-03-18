<?php

namespace App\Messaging\Http\Controllers;

use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Messaging\Models\Message;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Messaging\Services\CreateMessageService;
use App\Messaging\Http\Requests\ShowMessageRequest;
use App\Messaging\Http\Requests\CreateMessageRequest;
use App\Messaging\Http\Requests\UpdateMessageRequest;
use App\Messaging\Http\Requests\DeleteMessageRequest;
use App\Messaging\Http\Requests\AudioUploadMessageRequest;

class MessagesController
{
    public function index(Request $request)
    {
        $query = Message::whereNotNull('id')->latest();

        if ($conversationId = $request->input('conversation_id')) {
            $query->where('conversation_id', $conversationId);
        }

        return response()->paginate($query, 30);
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

    public function audioUpload(AudioUploadMessageRequest $request)
    {
        $audioFile = $request->file('audio_file');
        $fileLocalPath = Storage::cloud()->putFileAs(
            'messages/audio',
            $audioFile,
            Uuid::uuid4()->toString() . '.' . $audioFile->getClientOriginalExtension());
    }

    public function destroy(DeleteMessageRequest $request, Message $message)
    {
        $message->delete();

        return response()->json();
    }
}
