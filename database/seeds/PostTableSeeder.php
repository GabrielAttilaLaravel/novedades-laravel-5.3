<?php

use App\Post;
use Illuminate\Database\Seeder;

class PostTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = \App\User::all();

        foreach ($users as $user) {
            $posts = factory(Post::class, 10)->make();

            $user->posts()->saveMany($posts);
        }


    }
}
