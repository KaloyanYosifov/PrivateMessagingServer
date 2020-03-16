<?php

namespace App;

use App\Messaging\Models\Message;
use Laravel\Passport\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use App\Messaging\Models\Conversation;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function conversations(): BelongsToMany
    {
        return $this->belongsToMany(Conversation::class)->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function sentMessages(int $conversationId): HasMany
    {
        return $this->messages()->where('conversation_id', $conversationId);
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * @param Conversation|int $conversation
     * @return bool
     */
    public function isInConversation($conversation): bool
    {
        $conversationId = $conversation;

        if ($conversation instanceof Conversation) {
            $conversationId = $conversation->id;
        }

        return $this->conversations()->where('conversation_id', $conversationId)->count() > 0;
    }
}
