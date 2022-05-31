<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
        // \App\Models\User::factory(10)->create();

        $this->call(LaratrustSeeder::class);
        $user = \App\Models\User::create([
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'super@weka.com',
            'password' => bcrypt('12345678'),

        ]);

        $user->attachRole('super_admin');


    }
}
