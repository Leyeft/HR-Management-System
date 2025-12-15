<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

use App\Http\Controllers\DepartmentController;

Route::middleware(['auth'])->group(function () {
    Route::resource('departments', DepartmentController::class);
});

use App\Http\Controllers\EmployeeController;

Route::middleware(['auth'])->group(function () {
    Route::resource('employees', EmployeeController::class);
});
Route::middleware(['auth'])->group(function () {
    Route::get('/leave-requests', [LeaveRequestController::class, 'index'])
        ->name('leave.index');

    Route::get('/leave-requests/create', [LeaveRequestController::class, 'create'])
        ->name('leave.create');

    Route::post('/leave-requests', [LeaveRequestController::class, 'store'])
        ->name('leave.store');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/leave-approvals', [LeaveApprovalController::class, 'index'])
        ->name('leave.approvals');

    Route::post('/leave-approvals/{leave}/approve', [LeaveApprovalController::class, 'approve'])
        ->name('leave.approve');

    Route::post('/leave-approvals/{leave}/reject', [LeaveApprovalController::class, 'reject'])
        ->name('leave.reject');
});

