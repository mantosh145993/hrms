<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Console\MarkHolidayAttendance;

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
        $schedule->call(new MarkHolidayAttendance())->dailyAt('00:05');  
        $schedule->command('attendance:auto-checkout')->dailyAt('20:01');
    })->create();
