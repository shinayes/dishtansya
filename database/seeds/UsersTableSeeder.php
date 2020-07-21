<?php

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        (new User)->create([
            'name' => "dishtansya",
            'email' => "dishtansya@gmail.com",
            'email_verified_at' => now(),
            'password' => Hash::make('password123!')
        ]);
    }
}
