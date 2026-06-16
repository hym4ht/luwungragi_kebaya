<?php

use App\Http\Controllers\Admin\CostumeController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\RentalController as AdminRentalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\DashboardController as CustomerOrdersController;
use App\Http\Controllers\Customer\RentalController as CustomerRentalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\Owner\DashboardController as OwnerDashboardController;
use App\Http\Controllers\Owner\ReportController as OwnerReportController;
use App\Http\Controllers\Owner\ProfileController as OwnerProfileController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/catalog/{costume}', [HomeController::class, 'show'])->name('catalog.show');

// Midtrans webhook — no auth, CSRF excluded in bootstrap/app.php
Route::post('/payment/webhook/midtrans', [MidtransController::class, 'webhook'])
    ->name('midtrans.webhook');

Route::middleware('jwt.guest')->group(function (): void {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('auth.login');
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login.store');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('auth.register');
    Route::post('/register', [AuthController::class, 'register'])->name('auth.register.store');
});

Route::middleware('jwt.auth')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::prefix('customer')->name('customer.')->middleware('role:customer')->group(function (): void {
        Route::get('/orders', [CustomerOrdersController::class, 'index'])->name('orders');
        Route::post('/rentals', [CustomerRentalController::class, 'store'])->name('rentals.store');
        Route::get('/rentals/{rental}', [CustomerRentalController::class, 'show'])->name('rentals.show');
        Route::get('/rentals/{rental}/pdf', [CustomerRentalController::class, 'downloadPdf'])->name('rentals.pdf');

        // Midtrans Snap token generation (AJAX)
        Route::post('/rentals/{rental}/midtrans-token', [MidtransController::class, 'generateToken'])
            ->name('rentals.midtrans.token');
        Route::post('/rentals/{rental}/midtrans-sync', [MidtransController::class, 'syncStatus'])
            ->name('rentals.midtrans.sync');
    });

    Route::prefix('admin')->name('admin.')->middleware('role:admin')->group(function (): void {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/costumes', [CostumeController::class, 'index'])->name('costumes.index');
        Route::post('/costumes', [CostumeController::class, 'store'])->name('costumes.store');
        Route::get('/costumes/{costume}/edit', [CostumeController::class, 'edit'])->name('costumes.edit');
        Route::put('/costumes/{costume}', [CostumeController::class, 'update'])->name('costumes.update');
        Route::delete('/costumes/{costume}', [CostumeController::class, 'destroy'])->name('costumes.destroy');

        Route::get('/customers', [AdminCustomerController::class, 'index'])->name('customers.index');

        Route::get('/rentals', [AdminRentalController::class, 'index'])->name('rentals.index');
        Route::patch('/rentals/{rental}', [AdminRentalController::class, 'update'])->name('rentals.update');
        Route::get('/rentals/{rental}', [AdminRentalController::class, 'show'])->name('rentals.show');
        Route::patch('/rentals/{rental}/status', [AdminRentalController::class, 'updateStatus'])->name('rentals.status');
        Route::patch('/payments/{payment}/status', [AdminRentalController::class, 'updatePaymentStatus'])->name('payments.status');
        Route::post('/rentals/{rental}/returns', [AdminRentalController::class, 'storeReturn'])->name('rentals.returns.store');
    });

    Route::prefix('owner')->name('owner.')->middleware('role:owner')->group(function (): void {
        Route::get('/dashboard', [OwnerDashboardController::class, 'index'])->name('dashboard');

        // Owner Reports
        Route::get('/reports/financial', [OwnerReportController::class, 'financial'])->name('reports.financial');
        Route::get('/reports/transactions', [OwnerReportController::class, 'transactions'])->name('reports.transactions');
        Route::get('/reports/top-items', [OwnerReportController::class, 'topItems'])->name('reports.top-items');
        Route::get('/reports/returns', [OwnerReportController::class, 'returns'])->name('reports.returns');

        // Owner Profile
        Route::get('/profile', [OwnerProfileController::class, 'index'])->name('profile');
    });

    Route::get('/reports', [ReportController::class, 'index'])
        ->middleware('role:admin,owner')
        ->name('reports.index');
});
