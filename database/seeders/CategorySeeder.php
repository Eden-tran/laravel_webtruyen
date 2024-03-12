<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'name' => 'truyện hành động',
            'user_id' => 1,
            'description' => 'truyện hành động',
            'active' => '2',
            'slug' => 'truyen-hanh-dong',
        ];
        $data2 = [
            'name' => 'truyện khoa học',
            'user_id' => 1,
            'description' => 'truyện khoa học',
            'active' => '2',
            'slug' => 'truyen-khoa-hoc',
        ];
        Category::create($data);
        Category::create($data2);
    }
}
