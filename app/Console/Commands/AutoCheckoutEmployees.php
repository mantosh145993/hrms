<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use Carbon\Carbon;

class AutoCheckoutEmployees extends Command
{
    protected $signature = 'attendance:auto-checkout';
    protected $description = 'Automatically checkout employees at 8 PM if they forgot to checkout';

    public function handle()
    {
        $now = Carbon::now('Asia/Kolkata');
        $today = $now->toDateString();
        $cutoff = Carbon::createFromTime(20, 0, 0, 'Asia/Kolkata'); // 8 PM

        $attendances = Attendance::whereDate('work_date', $today)
            ->whereNull('check_out_at') // not checked out
            ->whereNotNull('check_in_at') // already checked in
            ->get();

        foreach ($attendances as $attendance) {
            $attendance->check_out_at = $cutoff;

            // Calculate worked minutes
            $attendance->worked_minutes = Carbon::parse($attendance->check_in_at)
                ->diffInMinutes($cutoff);

            $attendance->status = $attendance->status ?? 'present';

            $attendance->save();
        }

        $this->info('Auto checkout completed successfully.');
    }
}
