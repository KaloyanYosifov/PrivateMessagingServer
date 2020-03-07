<?php

use App\User;
use Illuminate\Database\Seeder;
use App\Messaging\Models\Message;

class ConversationsTableSeeder extends Seeder
{

    const MAX_MESSAGES_WITHIN_CONVERSATION = 30;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (User::count() <= 0) {
            $this->call(UsersTableSeeder::class);
        }

        $this->createConversations();
    }

    protected function createConversations()
    {
        $users = User::all();

        foreach ($users as $user) {
            foreach ($users as $user2) {
                if ($user->id === $user2->id) {
                    continue;
                }

                $users = [$user, $user2];
                $conversation = \App\Messaging\Models\Conversation::findOrCreate($user, $user2);

                for ($messageIndex = 0; $messageIndex < static::MAX_MESSAGES_WITHIN_CONVERSATION; $messageIndex++) {
                    $senderIndex = mt_rand(0, 1);

                    factory(Message::class)->create([
                        'conversation_id' => $conversation->id,
                        'user_id' => $users[$senderIndex],
                    ]);
                }
            }
        }
    }
}
