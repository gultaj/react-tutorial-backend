<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Conversation;

class ConversationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Conversation::truncate();
        DB::table('conversation_user')->truncate();

        $users = User::get();

        foreach ($users as $user) {
            for($i = 0, $count = rand(0, 10); $i < $count; $i++) {
                $rUser = $users->random();
                $conversation = $user->conversations->intersect($rUser->conversations)->first();
                if (!$conversation) {
                    $conversation = Conversation::create();
                    $conversation->users()->saveMany([$user, $rUser]);
                }
            }
        }
    }
}
