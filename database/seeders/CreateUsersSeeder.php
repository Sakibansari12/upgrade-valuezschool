<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = [
            [
                'name' => 'SuperAdmin',
                'email' => 'superadmin@gmail.com',
                'usertype' => 'superadmin',
                'password' => bcrypt('123456'),
            ],
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'usertype' => 'admin',
                'password' => bcrypt('123456'),
            ],
            [
                'name' => 'Teacher',
                'email' => 'demo@gmail.com',
                'usertype' => 'teacher',
                'password' => bcrypt('123456'),
            ],
        ];

        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
