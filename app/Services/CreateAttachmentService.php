<?php

namespace App\Services;

use App\Attachment;
use Ramsey\Uuid\Uuid;
use App\Enums\AttachmentType;
use Illuminate\Support\Facades\Storage;

class CreateAttachmentService
{
    public function create(
        \Symfony\Component\HttpFoundation\File\File $file,
        AttachmentType $attachmentType,
        ?int $duration = null
    ): Attachment {
        $path = Storage::cloud()->putFileAs(
            'attachments/' . $attachmentType->getValue(),
            $file,
            Uuid::uuid4()->toString() . '.' . $file->guessExtension());

        return Attachment::create([
            'type' => $attachmentType,
            'path' => $path,
            'duration_in_seconds' => $duration,
        ]);
    }
}
