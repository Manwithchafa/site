<?php

namespace App\Console;

use App\Jobs\RetryFailedSmsJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // Retry failed SMS every 5 minutes
        $schedule->job(new RetryFailedSmsJob())->everyFiveMinutes();
    }
}
