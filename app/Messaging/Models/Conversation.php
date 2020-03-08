<?php

namespace App\Messaging\Models;

use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $appends = ['last_message'];

    public function users()
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function getLastMessageAttribute(): ?Message
    {
        /**
         * @var Message|null $message
         */
        $message = $this->messages()->latest()->first();

        return $message;
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
        $table = (new ConversationUser())->getTable();
        $foundConversation = DB::table("$table as conversation_user1")
            ->select('*')
            ->where('conversation_user1.user_id', $user->id)
            ->join("$table as conversation_user2", 'conversation_user2.conversation_id', '=', 'conversation_user1.conversation_id')
            ->where('conversation_user2.user_id', $user2->id)
            ->pluck('conversation_id')
            ->toArray();

        // if the conversation pivot hasn't found two records
        // it means that one of the users are not in a conversation
        if (!$foundConversation) {
            return tap(Conversation::create(), function (Conversation $conversation) use ($user, $user2) {
                $conversation->users()->attach([$user->id, $user2->id]);
            });
        }

        return Conversation::find($foundConversation[0]);
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
