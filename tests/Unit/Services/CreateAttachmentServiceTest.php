<?php

namespace Tests\Unit\Services;

use App\Enums\AttachmentType;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use App\Services\CreateAttachmentService;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CreateAttachmentServiceTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_stores_attachments()
    {
        Storage::fake(config('filesystems.cloud'));

        $attachmentService = app()->make(CreateAttachmentService::class);
        $file = UploadedFile::fake()->createWithContent('audio.aac', 'audio test');

        $attachment = $attachmentService->create($file, AttachmentType::AUDIO());

        Storage::cloud()->assertExists($attachment->path);

        $this->assertRegExp('~^attachments/' . AttachmentType::AUDIO() . '~', $attachment->path);
        $this->assertRegExp('~\.aac$~', $attachment->path);
    }

    /** @test */
    public function it_stores_other_type_attachments()
    {
        Storage::fake(config('filesystems.cloud'));

        $attachmentService = app()->make(CreateAttachmentService::class);
        $file = UploadedFile::fake()->createWithContent('image.png', 'image content');

        $attachment = $attachmentService->create($file, AttachmentType::IMAGE());

        Storage::cloud()->assertExists($attachment->path);

        $this->assertRegExp('~^attachments/' . AttachmentType::IMAGE() . '~', $attachment->path);
        $this->assertRegExp('~\.png$~', $attachment->path);
    }
}
