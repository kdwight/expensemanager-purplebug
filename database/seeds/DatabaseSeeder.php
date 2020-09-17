<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \App\Role::create([
            'name' => 'admin',
            'description' => 'Administrator'
        ]);

        \App\User::create([
            'name' => 'PurpleBug Exam',
            'email' => 'purplebug@exam.test',
            'password' => bcrypt('password'),
            'role_id' => 1,
        ]);

        // factory(App\User::class, 20)->create();
    }
}
