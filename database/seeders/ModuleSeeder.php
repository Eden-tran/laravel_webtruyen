<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'name' => 'User',
            'title' => 'Quản lý người dùng',
        ];
        $data1 = [
            'name' => 'Manga',
            'title' => 'Quản lý truyện',
        ];
        $data2 = [
            'name' => 'Chapter',
            'title' => 'Quản lý chương',
        ];
        $data3 = [
            'name' => 'Category',
            'title' => 'Quản lý danh mục',
        ];
        $data5 = [
            'name' => 'Comment',
            'title' => 'Quản lý bình luận',
        ];
        $data4 = [
            'name' => 'Group',
            'title' => 'Quản lý nhóm người dùng',
        ];

        Module::create($data);
        Module::create($data1);
        Module::create($data2);
        Module::create($data3);
        Module::create($data4);
        Module::create($data5);


        //
    }
}
