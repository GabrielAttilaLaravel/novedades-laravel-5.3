<?php

use App\User;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
           'name' => 'Gabriel Moreno',
            'email' => 'gabrieljmorenot@gmail.com',
            'password' => encrypt('123123')
        ]);

        factory(User::class,10)->create();
    }
}
