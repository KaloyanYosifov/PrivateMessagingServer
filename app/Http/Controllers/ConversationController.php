<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Messaging\Models\Conversation;
use App\Messaging\Http\Requests\ShowConversationRequest;
use App\Messaging\Http\Requests\DeleteConversationRequest;

class ConversationController extends Controller
{
    public function index(Request $request)
    {
        return response()->paginate(Auth::user()->conversations(), 30);
    }

    public function show(ShowConversationRequest $request, Conversation $conversation)
    {
        return response()->json($conversation);
    }

    public function delete(DeleteConversationRequest $request, Conversation $conversation)
    {
        $conversation->delete();

        return response()->json([]);
    }
}
