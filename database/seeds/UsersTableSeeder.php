<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\User::class)->create([
            'email' => 'koko@example.com',
        ]);

        factory(\App\User::class)->create([
            'email' => 'vicky@example.com',
        ]);
    }
}
