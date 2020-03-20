<?php

namespace Tests\Unit;

use App\Attachment;
use Tests\TestCase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AttachmentTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_returns_the_url()
    {
        $attachment = factory(Attachment::class)->create([
            'path' => 'attachments/some-file',
        ]);

        $this->assertEquals(Storage::cloud()->url('attachments/some-file'), $attachment->url);
    }
}
