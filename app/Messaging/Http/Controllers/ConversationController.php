<?php

namespace App\Messaging\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Messaging\Models\Conversation;
use App\Messaging\Http\Requests\ShowConversationRequest;
use App\Messaging\Http\Requests\DeleteConversationRequest;

class ConversationController
{
    public function index(Request $request)
    {
        return response()->paginate(
            Auth::user()
                ->conversations()
                ->with([
                    'users' => function ($query) {
                        $query->where('user_id', '<>', Auth::id());
                    },
                ])
                ->latest('updated_at')
            ,
            30
        );
    }

    public function show(ShowConversationRequest $request, Conversation $conversation)
    {
        return response()->json($conversation);
    }

    public function destroy(DeleteConversationRequest $request, Conversation $conversation)
    {
        $conversation->delete();

        return response()->json([]);
    }
}
