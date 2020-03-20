<?php

namespace App\Messaging\Http\Requests;

use App\User;
use Ramsey\Uuid\Uuid;
use App\Enums\AttachmentType;
use Illuminate\Support\Facades\Auth;
use App\Messaging\Models\Conversation;
use Illuminate\Support\Facades\Storage;
use App\Services\CreateAttachmentService;
use App\Messaging\Builders\MessageBuilder;
use Illuminate\Foundation\Http\FormRequest;

class CreateMessageRequest extends FormRequest
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
            'receiver_id' => 'required_without:conversation_id|exists:users,id',
            'conversation_id' => 'required_without:receiver_id|exists:conversations,id',
            'text' => 'required_without:audio_file|string',
            'audio_file' => 'nullable|file',
            'duration' => 'required_with:audio_file|int',
        ];
    }

    public function getBuilder(): MessageBuilder
    {
        return tap(app()->make(MessageBuilder::class), function (MessageBuilder $builder) {
            if ($conversationId = $this->input('conversation_id')) {
                $builder->setConversation(Conversation::find($conversationId));
            } elseif ($receiverId = $this->input('receiver_id')) {
                $builder->setReceiver(User::find($receiverId));
            }

            if ($audioFile = $this->file('audio_file')) {
                $attachment = app()->make(CreateAttachmentService::class)
                    ->create(
                        $audioFile,
                        AttachmentType::AUDIO(),
                        $this->input('duration')
                    );

                $builder->setAttachment($attachment);
            }

            if ($text = $this->input('text')) {
                $builder->setText($text);
            }

            $builder->setSender(Auth::user());
        });
    }
}
