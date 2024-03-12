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
            'permissions' => '{"User":{"0":"View","1":"Add","2":"Edit","3":"Delete","Scope":"1"},"Manga":{"0":"View","1":"Add","2":"Edit","3":"Delete","Scope":"1"},"Chapter":{"0":"View","1":"Add","2":"Edit","3":"Delete","Scope":"1"},"Category":{"0":"View","1":"Add","2":"Edit","3":"Delete","Scope":"1"},"Group":{"0":"View","1":"Add","2":"Edit","3":"Delete","4":"Decentralize","Scope":"1"}}'
        ];
        $data = [
            'name' => 'Normal user',
            'user_id' => 0,
            'active' => 2,
            'permissions' => null
        ];
        Group::create($data1);
        Group::create($data);
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
