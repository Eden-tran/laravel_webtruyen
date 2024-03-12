<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use App\Models\Manga;

class MangaSeeder extends Seeder
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
                'user_id' => 1,
                'name' => 'One Piece',
                'another_name' => 'Vua hải tặc',
                'active' => '2',
                'author' => 'Oda Eiichiro',
                'describe' => 'Vua hải tặc, Đảo hải tặc, Đi tìm kho báu',
                'is_finished' => '2',
                'image_cover' => 'default.jpg',
                'slug' => 'one-piece',
            ],
            [
                'user_id' => 1,
                'name' => 'Jujutsu Kaisen',
                'another_name' => 'Yuji Itadori',
                'active' => '2',
                'author' => 'Gege Akutami',
                'describe' => 'Chú thuật, Quỷ dữ, Học sinh trung học',
                'is_finished' => '2',
                'image_cover' => 'default.jpg',
                'slug' => 'jujutsu-kaisen',
            ],
            [
                'user_id' => 1,
                'name' => 'One Punch Man',
                'another_name' => 'Saitama',
                'active' => '2',
                'author' => 'ONE',
                'describe' => 'Một cú đấm, Siêu anh hùng, Hài hước',
                'is_finished' => '1',
                'image_cover' => 'default.jpg',
                'slug' => 'one-punch-man',
            ],
            [
                'user_id' => 1,
                'name' => 'Dragon Ball',
                'another_name' => 'Son Goku',
                'active' => '2',
                'author' => 'Akira Toriyama',
                'describe' => 'Siêu Saiyan, Bảy viên ngọc rồng, Giải cứu thế giới',
                'is_finished' => '1',
                'image_cover' => 'default.jpg',
                'slug' => 'dragon-ball',
            ],
            [
                'user_id' => 1,
                'name' => 'Bleach',
                'another_name' => 'Kurosaki Ichigo',
                'active' => '2',
                'author' => 'Tite Kubo',
                'describe' => 'Shinigami, Linh hồn',
                'is_finished' => '1',
                'image_cover' => 'default.jpg',
                'slug' => 'bleach',
            ],
            [
                'user_id' => 1,
                'name' => 'Naruto',
                'another_name' => 'Uzumaki Naruto',
                'active' => '2',
                'author' => 'Masashi Kishimoto',
                'describe' => 'Ninja, Cửu vĩ, Trở thành Hokage',
                'is_finished' => '1',
                'image_cover' => 'default.jpg',
                'slug' => 'naruto',
            ],
            [
                "user_id" => 1,
                "name" => "Detective Conan",
                "another_name" => "Konan Edogawa",
                "active" => 2,
                "author" => "Aoyama Gosho",
                "describe" => "Thiên tài thám tử, Đứa trẻ có bộ não của người lớn, Giải quyết các vụ án bí ẩn",
                "is_finished" => 0,
                'image_cover' => 'default.jpg',
                "slug" => "detective-conan"
            ]
        ];

        $category = Category::all();
        foreach ($data as $item) {
            Manga::create($item)->categories()->attach($category->random());
        }
        // Manga::create($data2);
    }
}
