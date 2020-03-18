<?php

namespace App\Messaging\Models;

use App\User;
use Ramsey\Uuid\Uuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    protected $fillable = [
        'text',
        'audio_path',
    ];

    protected $hidden = [
        'user_id',
        'audio_path',
    ];

    protected $appends = [
        'audio_url',
    ];

    protected $with = ['user'];

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

    public function getAudioUrlAttribute(): string
    {
        if (!$this->attributes['audio_path']) {
            return '';
        }

        return Storage::cloud()->url($this->attributes['audio_path']);
    }

    public function getRouteKeyName(): string
    {
        return 'unique_id';
    }
}
