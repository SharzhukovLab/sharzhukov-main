<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


// ─── Automated backups (spatie/laravel-backup) ───
Schedule::command('backup:clean')->dailyAt('02:30');
Schedule::command('backup:run')->dailyAt('03:00');