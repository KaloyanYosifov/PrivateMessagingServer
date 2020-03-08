<?php

use App\User;
use Illuminate\Support\Carbon;
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
        $carbonNow = Carbon::now();
        $randomDates = [
            $carbonNow->subHours(5),
            $carbonNow->subDays(1),
            $carbonNow->subHours(3),
            $carbonNow->subDays(3),
            $carbonNow->subHours(15),
            $carbonNow->subHours(7),
            $carbonNow->subDays(5),
            $carbonNow->subMonths(1),
            $carbonNow->subDays(6),
            $carbonNow->subMonths(6),
        ];

        foreach ($users as $user) {
            foreach ($users as $user2) {
                if ($user->id === $user2->id) {
                    continue;
                }

                $usersToChooseFrom = [$user, $user2];

                $conversation = \App\Messaging\Models\Conversation::findOrCreate($user, $user2);

                for ($messageIndex = 0; $messageIndex < static::MAX_MESSAGES_WITHIN_CONVERSATION; $messageIndex++) {
                    $senderIndex = mt_rand(0, 1);
                    $randomDateIndex = mt_rand(0, count($randomDates) - 1);

                    factory(Message::class)->create([
                        'conversation_id' => $conversation->id,
                        'user_id' => $usersToChooseFrom[$senderIndex],
                        'created_at' => $randomDates[$randomDateIndex],
                        'updated_at' => $randomDates[$randomDateIndex],
                    ]);
                }
            }
        }
    }
}
