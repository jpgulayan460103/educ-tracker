<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'last_name' => 'admin',
            'first_name' => 'admin',
            'middle_name' => 'admin',
            'ext_name' => null,
            'username' => 'admin',
            'password' => 'admin',
            'user_role' => 'Admin',
        ]);

        $user->is_active = 1;
        $user->save();
    }
}
