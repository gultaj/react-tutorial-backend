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

        $users = User::get();

        foreach ($users as $user) {
            for($i = 0, $count = rand(0, 10); $i < $count; $i++) {
                $r = $users->random();
                $c = Conversation::where(function($query) use ($user, $r) {
                        $query->where('user_one', '=', $user->id)
                            ->where('user_two', '=', $r->id);
                    })->orWhere(function($query) use ($user, $r) {
                        $query->where('user_one', '=', $r->id)
                            ->where('user_two', '=', $user->id);
                    })->first();
                if (!$c) {
                    Conversation::create([
                        'user_one' => $user->id,
                        'user_two' => $r->id
                    ]);
                }
            }
        }
    }
}
