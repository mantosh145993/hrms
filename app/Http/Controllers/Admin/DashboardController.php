<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{User,Holiday,Task,Leave};
class DashboardController extends Controller
{
    public function index()
    {
        $totalEmployees = User::count();
        $totalHolidays = Holiday::count();
        $totalTasks = Task::count();
        $completedTasks = Task::where('status', 'pending')->count();
        $pendingLeaves = Leave::where('status', 'pending')->count();
        $taskPercentage = $totalTasks ? ($completedTasks / $totalTasks) * 100 : 0;
        $employees = User::all();
        return view('admin.dashboard', compact(
            'totalEmployees',
            'totalHolidays',
            'totalTasks',
            'taskPercentage',
            'pendingLeaves',
            'employees'
        ));
    }
}
