<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;

class MessagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Message::truncate();

        $conversations = Conversation::get();

        foreach ($conversations as $conversation) {
            $users = $conversation->users;
            for ($i = 0, $count = rand(1, 5); $i < $count; $i++) {
                $message = factory(Message::class)->create();
                $message->conversation_id = $conversation->id;
                if (rand(0, 1)) {
                    $message->to_user_id = $users[0]->id;
                    $message->from_user_id = $users[1]->id;
                } else {
                    $message->to_user_id = $users[1]->id;
                    $message->from_user_id = $users[0]->id;
                }
                $message->save();
            }
        }
    }
}
