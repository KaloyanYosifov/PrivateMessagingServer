<?php

namespace App\Messaginng\Http\Requests;

use App\User;
use Illuminate\Support\Facades\Auth;
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
            'to_user_id' => 'required|exists:users,id',
            'text' => 'required|string',
        ];
    }

    public function getBuilder(): MessageBuilder
    {
        $builder = app()->make(MessageBuilder::class);
        $fromUser = User::find($this->input('to_user_id'));

        return $builder->setFromUser(Auth::user())
            ->setToUser($fromUser)
            ->setText($this->input('text'));
    }
}
