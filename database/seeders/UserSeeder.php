<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $data = [
            [
                'name' => 'Eden',
                'email' => 'bin13199@gmail.com',
                'password' => Hash::make('12345678'),
                'active' => 2,
                'user_id' => 0,
                'avatar' => 'default.jpg',
                'group_id' => 1,
                'email_verified_at' => now()
            ],
            // [
            //     'name' => 'Quang',
            //     'email' => 'user1@gmail.com',
            //     'password' => Hash::make('12345678'),
            //     'active' => 2,
            //     'user_id' => 1,
            //     'avatar' => 'default.jpg',
            //     'group_id' => 2,
            //     'email_verified_at' => now()
            // ],
            //[
            //     'name' => 'Jayce',
            //     'email' => 'binknight13199@gmail.com',
            //     'password' => Hash::make('12345678'),
            //     'active' => 2,
            //     'user_id' => 0,
            //     'avatar' => 'default.jpg',
            //     'group_id' => 2,
            //     'email_verified_at' => now()
            // ]
        ];
        // $data2 = [
        //     'name' => 'Bin',
        //     'email' => 'tranngocquang13199@gmail.com',
        //     'password' => Hash::make('12345678'),
        //     'avatar' => 'default.jpg',
        //     'user_id' => 1,
        //     'active' => 2,
        //     'group_id' => 2,

        // ];
        foreach ($data as $item) {
            User::create($item);
        }
        // User::create($data2);
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
