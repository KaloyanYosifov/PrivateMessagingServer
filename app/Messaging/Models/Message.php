<?php

namespace App\Messaging\Models;

use App\User;
use App\Attachment;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'text',
        'attachment_id',
    ];

    protected $hidden = [
        'user_id',
        'attachment_id',
    ];

    protected $with = ['user', 'attachment'];

    public static function boot()
    {
        parent::boot();

        static::creating(function (Message $message) {
            $message->unique_id = Uuid::uuid4();

            Conversation::updateTheTimestamps($message->conversation_id);
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

    public function attachment()
    {
        return $this->belongsTo(Attachment::class);
    }

    public function getRouteKeyName(): string
    {
        return 'unique_id';
    }
}
