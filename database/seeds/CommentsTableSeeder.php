<?php

use Illuminate\Database\Seeder;

class CommentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	App\Comment::truncate();

        $users = App\User::all();

    	factory(App\Comment::class, 20)->create()->each(function($comment) use ($users) {

            $comment->author()->associate($users->random())->save();

        });
    }
}
