<?php

namespace App\Helpers;

class TimeHelper
{
    public static function formatSeconds($totalSeconds): string
    {
        if (!$totalSeconds || $totalSeconds <= 0) {
            return "0h 0m 0s";
        }

        $hours = intdiv($totalSeconds, 3600);
        $minutes = intdiv($totalSeconds % 3600, 60);
        $seconds = $totalSeconds % 60;

        return "{$hours}h {$minutes}m {$seconds}s";
    }
}
