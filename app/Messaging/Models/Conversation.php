<?php

namespace App\Messaging\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * @param User $user
     * @param User $user2
     * @return Conversation|null
     */
    public static function findOrCreate(User $user, User $user2): ?Conversation
    {
        /**
         * @var Conversation $conversation
         */
        $conversation = Conversation::whereIn('user_id', [$user->id, $user2->id])->first();

        if (!$conversation) {
            return tap($user->conversations()->create(), function (Conversation $conversation) use ($user2) {
                $conversation->users()->attach([$user2->id]);
            });
        }

        return $conversation;
    }
}
