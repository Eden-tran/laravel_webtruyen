<?php

namespace Database\Seeders;

use App\Models\Group;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $data1 = [
            'name' => 'Super Admin',
            'user_id' => 0,
            'active' => 2,
        ];
        $data = [
            'name' => 'Normal user',
            'user_id' => 0,
            'active' => 2,
        ];
        Group::create($data1);
        Group::create($data);
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}