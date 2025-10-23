<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\admin\FeedbackController;
use App\Http\Controllers\Employee\DashboardController as EmployeeDashboard;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\TaskController;
use App\Http\Controllers\Admin\ShiftController;
use App\Http\Controllers\Admin\LeaveController;
use App\Http\Controllers\Admin\LeaveTypeController;

// Test URL 
Route::get('gallery', function(){
    return view('gallery');
});

// Login Root 
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
// End Login Root 
Route::get('/', function () {
    return view('welcome');
});
// Employee Route
Route::middleware(['auth','role:employee'])->group(function () {
    Route::get('/employee/dashboard', [EmployeeDashboard::class, 'index'])->name('employee.dashboard');
    Route::get('/profile/show', [EmployeeDashboard::class, 'profileUpdateShow'])->name('profile.show');
    Route::put('/profile/update/{id}', [EmployeeDashboard::class, 'profileUpdate'])->name('profile.update');
    Route::get('/employee/assigned-task', [EmployeeDashboard::class, 'assignedTask'])->name('employee.assigned');
    Route::post('/tasks/update-status', [EmployeeDashboard::class, 'updateStatus'])->name('tasks.updateStatus');
    Route::get('/employee/attendance', [EmployeeDashboard::class, 'attendance'])->name('employee.attendance');
    Route::get('/employee/holoday', [EmployeeDashboard::class, 'holiday'])->name('employee.holiday');
    // Route::get('/employee/dashboard', [AttendanceController::class, 'employeeDashboard']);
    Route::post('/attendance/check-in', [AttendanceController::class, 'checkIn'])->name('attendance.checkIn');
    Route::post('/attendance/check-out', [AttendanceController::class, 'checkOut'])->name('attendance.checkOut');
    Route::get('/attendance/day/{date}', [AttendanceController::class, 'dayView']);
    Route::get('/attendance/month/{ym}', [AttendanceController::class, 'monthView']);
    Route::resource('leaves', LeaveController::class);
    Route::get('employee/feedback',[ EmployeeDashboard::class,'employeeFeedback'] )->name('employee.feedback');
});
// End Employee Route
// Admin Route
Route::prefix('admin')->middleware(['auth','role:admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AttendanceController::class, 'dashboard']);
    Route::get('/attendances', [\App\Http\Controllers\Admin\AttendanceController::class, 'index']);
    Route::post('/attendances/{attendance}/update', [\App\Http\Controllers\Admin\AttendanceController::class, 'update']);
    Route::post('/recompute/{ym}', [\App\Http\Controllers\Admin\AttendanceController::class, 'recomputeMonth']);
    // Route::get('/reports/month/{ym}/export', [ReportController::class, 'exportMonth']);
    Route::resource('/holidays', \App\Http\Controllers\Admin\HolidayController::class);
    Route::resource('feedbacks', \App\Http\Controllers\Admin\FeedbackController::class);
    Route::resource('users', UserController::class);
    Route::resource('holidays', \App\Http\Controllers\Admin\HolidayController::class);
    Route::resource('shifts', ShiftController::class);
    Route::resource('tasks', TaskController::class);
    Route::get('attendance', [\App\Http\Controllers\Admin\AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/attendance/data', [\App\Http\Controllers\Admin\AttendanceController::class, 'getData'])->name('attendance.data');
    Route::resource('leave-types', LeaveTypeController::class);
    Route::get('leaves', [LeaveTypeController::class,'getAppliedLeave'])->name('leave.applied');
    Route::post('leaves', [LeaveTypeController::class,'updateLeaveStatus'])->name('leave.status');
    Route::get('/attendance/report', [AttendanceController::class, 'attendanceReport'])->name('attendance.report');
    Route::get('/attendance/report/export', [AttendanceController::class, 'exportAttendanceReport'])->name('attendance.report.export');
    Route::get('attendance/export', [\App\Http\Controllers\Admin\AttendanceController::class, 'export'])->name('attendance.report.export');
    Route::post('update/reason',[LeaveTypeController::class,'updateReason'])->name('update.reason');
    Route::post('/leaves/update/{id}', [LeaveController::class, 'updateField'])->name('leaves.update');


});
// End Admin Route 