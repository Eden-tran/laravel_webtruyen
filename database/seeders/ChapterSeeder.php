<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chapter;

class ChapterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Chương 1',
                'manga_id' => '1',
                'user_id' => '1',
                'active' => 2,
                'status' => 2,
                'slug' => 'chuong-1',
            ],
            [
                'name' => 'Chương 2',
                'manga_id' => '1',
                'user_id' => '1',
                'active' => 2,
                'status' => 2,
                'slug' => 'chuong-2',
            ],
            [
                'name' => 'Chương 3',
                'manga_id' => '1',
                'user_id' => '1',
                'active' => 2,
                'status' => 2,
                'slug' => 'chuong-3',
            ],
            [
                'name' => 'Chương 1',
                'manga_id' => '2',
                'user_id' => '1',
                'active' => 2,
                'status' => 2,
                'slug' => 'chuong-1',
            ],
            [
                'name' => 'Chương 5',
                'manga_id' => '3',
                'user_id' => '1',
                'active' => 2,
                'status' => 2,
                'slug' => 'chuong-5',
            ],
            [
                'name' => 'Chương 1',
                'manga_id' => '4',
                'user_id' => '1',
                'active' => 2,
                'status' => 2,
                'slug' => 'chuong-1',
            ],
            [
                'name' => 'Chương 1',
                'manga_id' => '5',
                'user_id' => '1',
                'active' => 2,
                'status' => 2,
                'slug' => 'chuong-1',
            ],
            [
                'name' => 'Chương 1',
                'manga_id' => '6',
                'user_id' => '1',
                'active' => 2,
                'status' => 2,
                'slug' => 'chuong-1',
            ]
        ];
        foreach ($data as $item) {
            Chapter::create($item);
        }
    }
}
