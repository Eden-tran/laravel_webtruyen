<?php

namespace App\Console;

use App\Models\Chapter;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $schedule->command('inspire')->everyFiveSeconds()->appendOutputo('inspire.txt');
        // $schedule->command('inspire')->everyMinute()->appendOutputTo('inspire.txt');
        // $schedule->command('inspire')->everyFiveSeconds()->appendOutputTo('inspire.txt');
        $schedule->call(function () {
            $chapter = Chapter::where('name', '=', '')->get();
            foreach ($chapter as $item) {
                if (now() > Carbon::parse($item->created_at)->addMinutes(30) && $item->status == 1) {
                    $item->delete();
                    if (Storage::exists("public/tempChapter/$item->id")) {
                        Storage::deleteDirectory("public/tempChapter/$item->id");
                    }
                }
            }
        })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
