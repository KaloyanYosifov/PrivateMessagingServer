<?php

namespace App\Messaginng\Http\Requests;

use App\Messaging\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ShowMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $message = Message::with(['fromUser', 'toUser'])->find($this->route('message'));

        return Auth::id() === $message->fromUser->id || Auth::id() === $message->toUser->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
