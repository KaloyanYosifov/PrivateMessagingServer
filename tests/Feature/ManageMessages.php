<?php

namespace Tests\Feature;

use App\User;
use http\Message;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Http\UploadedFile;
use App\Messaging\Models\Conversation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ManageMessages extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_upload_audio_file()
    {
        $this->withoutExceptionHandling();

        Storage::fake(config('filesystems.cloud'));

        $file = UploadedFile::fake()->createWithContent('audio.aac', 'audio content');
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $conversation = Conversation::findOrCreate($user, $user2);

        Passport::actingAs($user);

        $response = $this->json('POST', route('messages.store'), [
            'audio_file' => $file,
            'conversation_id' => $conversation->id,
        ]);
    }
}
