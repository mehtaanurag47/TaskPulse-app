<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Automatic Task Reminder Scheduler
|--------------------------------------------------------------------------
*/

Schedule::command('app:send-task-reminders')
    ->everyTwentySeconds()
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/scheduler.log'));

/*
|--------------------------------------------------------------------------
| Default Laravel Inspire Command
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');