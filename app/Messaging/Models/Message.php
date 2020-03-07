<?php

namespace App\Messaging\Models;

use App\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'text',
    ];

    protected $hidden = [
        'user_id',
    ];

    protected $with = ['user'];

    public static function boot()
    {
        parent::boot();

        static::creating(function (Message $message) {
            $message->unique_id = Uuid::uuid4();
        });
    }

    public function conversation(): BelongsTo
    {
        return $this->belongsTo(Conversation::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName(): string
    {
        return 'unique_id';
    }
}
