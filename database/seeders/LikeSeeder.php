<?php

namespace Database\Seeders;

use App\Models\Like;
use App\Models\User;
use App\Models\Manga;
use Illuminate\Database\Seeder;


class LikeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $mangas = Manga::all();
        foreach ($users as $user) {
            $n = rand(1, count($mangas));
            for ($i = 0; $i < $n; $i++) {
                Like::firstOrCreate([
                    'manga_id' => $mangas->random()->id,
                    'user_id' => $user->id
                ]);
            }
        }
    }
}
