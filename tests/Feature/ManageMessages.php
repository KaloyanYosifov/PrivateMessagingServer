<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Laravel\Passport\Passport;
use Illuminate\Http\UploadedFile;
use App\Messaging\Models\Message;
use App\Messaging\Models\Conversation;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ManageMessages extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_create_message_with_audio_file()
    {
        Storage::fake(config('filesystems.cloud'));

        $file = UploadedFile::fake()->createWithContent('audio.aac', 'audio content');
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $conversation = Conversation::findOrCreate($user, $user2);
        $audioDuration = 20;

        Passport::actingAs($user);

        $this->assertTrue(Message::count() === 0);

        $this->json('POST', route('messages.store'), [
            'audio_file' => $file,
            'duration' => $audioDuration,
            'conversation_id' => $conversation->id,
        ])->assertOk();

        $this->assertTrue(Message::count() === 1);

        $message = Message::first();

        $this->assertNotNull($message->attachment);

        $this->assertSame($audioDuration, $message->attachment->duration_in_seconds);

        Storage::cloud()->exists($message->attachment->path);
    }

    /** @test */
    public function it_can_create_message()
    {
        $this->withoutExceptionHandling();
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $conversation = Conversation::findOrCreate($user, $user2);
        $text = 'Random message';

        Passport::actingAs($user);

        $this->assertTrue(Message::count() === 0);

        $this->json('POST', route('messages.store'), [
            'text' => $text,
            'conversation_id' => $conversation->id,
        ])->assertOk();

        $this->assertTrue(Message::count() === 1);

        $message = Message::first();

        $this->assertSame($text, $message->text);
    }
}
