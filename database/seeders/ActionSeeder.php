<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        // $actionArr = [
        //     'View',
        //     'Add',
        //     'Edit',
        //     'Delete',
        // ];
        $actions = [['name' => 'View'], ['name' => 'Add'], ['name' => 'Edit'], ['name' => 'Delete']];
        $modules = Module::all();
        foreach ($modules as $module) {
            $module->actions()->createMany($actions);
        }
    }
}