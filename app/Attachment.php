<?php

namespace App;

use App\Enums\AttachmentType;
use App\Messaging\Models\Message;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    protected $guarded = [];

    protected $appends = [
        'url',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function setTypeAttribute($value)
    {
        if ($value instanceof AttachmentType) {
            $this->attributes['type'] = $value->getValue();

            return;
        }

        $this->attributes['type'] = $value;
    }

    public function getUrlAttribute()
    {
        return Storage::cloud()->url($this->attributes['path']);
    }
}
