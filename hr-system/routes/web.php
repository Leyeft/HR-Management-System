<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\LeaveApprovalController;
use App\Http\Controllers\HRLeaveController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('welcome');
});

/*
|--------------------------------------------------------------------------
| Dashboard
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Departments
    Route::resource('departments', DepartmentController::class);

    // Employees
    Route::resource('employees', EmployeeController::class);

    // Leave requests (Employee)
    Route::get('/leave-requests', [LeaveRequestController::class, 'index'])
        ->name('leave.index');

    Route::get('/leave-requests/create', [LeaveRequestController::class, 'create'])
        ->name('leave.create');

    Route::post('/leave-requests', [LeaveRequestController::class, 'store'])
        ->name('leave.store');

    // Leave approvals (Head)
    Route::get('/leave-approvals', [LeaveApprovalController::class, 'index'])
        ->name('leave.approvals');

    Route::post('/leave-approvals/{leave}/approve', [LeaveApprovalController::class, 'approve'])
        ->name('leave.approve');

    Route::post('/leave-approvals/{leave}/reject', [LeaveApprovalController::class, 'reject'])
        ->name('leave.reject');

    // HR leave view (READ ONLY)
    Route::get('/hr/leave-requests', [HRLeaveController::class, 'index'])
        ->name('hr.leave.index');
});

require __DIR__ . '/auth.php';

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');
