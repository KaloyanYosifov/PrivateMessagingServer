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
        $conversationPivot = ConversationUser::whereIn('user_id', [$user->id, $user2->id])->first();

        if (!$conversationPivot) {
            return tap(Conversation::create(), function (Conversation $conversation) use ($user, $user2) {
                $conversation->users()->attach([$user->id, $user2->id]);
            });
        }

        return $conversationPivot->conversation;
    }

    public function hasUser(User $user): bool
    {
        return $this->users()->where('user_id', $user->id)->count() > 0;
    }

    /**
     * @param int[] $withoutUsers
     * @return $this
     */
    public function loadUsers(array $withoutUsers = []): self
    {
        $this->load([
            'users' => function ($query) use ($withoutUsers) {
                if (!$withoutUsers) {
                    return;
                }

                $query->whereNotIn('user_id', $withoutUsers);
            },
        ]);

        return $this;
    }
}
