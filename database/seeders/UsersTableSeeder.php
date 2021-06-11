<?php

namespace Database\Seeders;

use App\Models\User;
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
        $user = new User();
        $user->name = 'Belal';
        $user->email = 'belalkhan.dev@gmail.com';
        $user->username = 'belalkhan';
        $user->password = app('hash')->make('password');
        $user->save();
    }
}
