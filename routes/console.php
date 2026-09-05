<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Perintahkan robot untuk mengecek kepulangan setiap jam 12:01 malam
Schedule::command('departures:complete')->dailyAt('00:01');