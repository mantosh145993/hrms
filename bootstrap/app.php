<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })->withExceptions(function (Exceptions $exceptions): void {
        //
    })->withSchedule(function (Illuminate\Console\Scheduling\Schedule $schedule) {
        // $schedule->call(new \App\Console\MarkHolidayAttendance())->everyMinute();  
        // ->dailyAt('00:05')
    })->withSchedule(function (Illuminate\Console\Scheduling\Schedule $schedule) {
        // Run the auto-checkout command every day at 8:01 PM
        // $schedule->command('attendance:auto-checkout')->dailyAt('20:01');
        // $schedule->command('attendance:auto-checkout')->everyMinute();
    })
    //
    ->create();
