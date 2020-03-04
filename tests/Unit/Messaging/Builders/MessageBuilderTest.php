<?php

namespace Tests\Unit\Messaging\Builders;

use App\User;
use App\Messaging\Builders\MessageBuilder;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MessageBuilderTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function it_can_build_a_message()
    {
        $fromUser = factory(User::class)->create();
        $toUser = factory(User::class)->create();
        $text = 'Some text';
        $messageBuilder = app()->make(MessageBuilder::class);

        $message = $messageBuilder->setFromUser($fromUser)->setToUser($toUser)->setText($text)->build();

        $this->assertTrue($message->user->is($fromUser));
        $this->assertEquals($text, $message->text);
    }

    /** @test */
    public function it_throws_an_error_if_we_dont_have_one_of_the_fields()
    {
        $this->expectException(\InvalidArgumentException::class);

        $fromUser = factory(User::class)->create();
        $toUser = factory(User::class)->create();
        $messageBuilder = app()->make(MessageBuilder::class);

        $messageBuilder->setFromUser($fromUser)->setToUser($toUser)->build();
    }
}
