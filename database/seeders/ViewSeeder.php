<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\View;
use App\Models\Chapter;
use Illuminate\Database\Seeder;

class ViewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = User::all();
        $chapters = Chapter::all();
        foreach ($users as $user) {
            $n = rand(1, 100);
            for ($i = 0; $i < $n; $i++) {
                View::create([
                    'chapter_id' => $chapters->random()->id,
                    'user_id' => $user->id
                ]);
            }
        }
        for ($j = 0; $j < 100; $j++) {
            View::create([
                'chapter_id' => $chapters->random()->id,
                'user_id' => NULL,
            ]);
        }
    }
}
