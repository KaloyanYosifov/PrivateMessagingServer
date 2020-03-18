<?php

namespace App\Messaging\Http\Requests;

use App\User;
use Illuminate\Support\Facades\Auth;
use App\Messaging\Models\Conversation;
use App\Messaging\Builders\MessageBuilder;
use Illuminate\Foundation\Http\FormRequest;

class AudioUploadMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'audio_file' => 'file',
            'conversation_id' => 'exists:conversations,id',
        ];
    }
}
