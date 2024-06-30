<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\TenantController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Landlord\RentController;
use App\Http\Controllers\Landlord\LeaseController;
use App\Http\Controllers\Auth\TenantLoginController;
use App\Http\Controllers\Auth\LandlordLoginController;
use App\Http\Controllers\Auth\TenantProfileController;
use App\Http\Controllers\Auth\LandlordRegistrationController;
use App\Http\Controllers\Landlord\UtilityBillController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('homepage');
})->name('homepage');

Route::get('/All-landlords-Table', [LandlordRegistrationController::class, 'index'])->name('landlords.index');
// Route::get('/landlords/check', [LandlordRegistrationController::class, 'checkDataValidity'])->name('landlords.check');

Route::get('/all-tenants-table', [TenantController::class, 'index'])->name('tenants.index');
// Route::get('/tenants/check', [TenantController::class, 'checkDataValidity'])->name('tenants.check');

Route::get('/all-lease-table', [LeaseController::class, 'index2'])->name('lease.index');

// Tenant Login
Route::get('/login-tenant', [TenantController::class, 'showLoginForm'])->name('login-tenant');
Route::post('/login-tenant', [TenantController::class, 'login'])->name('tenant.login.post');

// Tenant Login and Stuff
Route::middleware(['tenant.auth'])->group(function () {
    Route::get('/tenant/dashboard', [TenantController::class, 'dashboard'])->name('tenant.dashboard');
    Route::post('/logout-tenant', [TenantController::class, 'logout'])->name('logout-tenant');
    Route::get('/tenant/profile', [TenantProfileController::class, 'show'])->name('tenant.profile');
    Route::post('/tenant/profile/upload', [TenantProfileController::class, 'upload'])->name('tenant.profile.upload');
    Route::post('/tenant/profile/update', [TenantProfileController::class, 'updateField'])->name('tenant.profile.update');

    // Update Password
    Route::get('/tenant/change-password', [TenantProfileController::class, 'showChangePasswordForm'])->name('tenant.change-password');
    Route::post('/tenant/change-password', [TenantProfileController::class, 'changePassword'])->name('tenant.change-password.update');

    // View lease
    Route::get('/tenant/leases', [LeaseController::class, 'showTenantLeases'])->name('tenant.leases');

    // View rent
    Route::get('/tenant/rent', [RentController::class, 'showRent'])->name('tenant.showRent');
    Route::post('/tenant/rent/upload-proof/{rentPaymentId}', [RentController::class, 'uploadProof'])->name('tenant.uploadProof');

    // View utility
    Route::get('/tenant/utility', [UtilityBillController::class, 'showUtility'])->name('tenant.showUtility');
    Route::post('/tenant/utility/upload-proof/{utility_bill_id}', [UtilityBillController::class, 'uploadProof'])->name('tenant.uploadUtilityProof');
});

// Landlord Signup and Login
Route::get('/signup', [LandlordRegistrationController::class, 'showRegistrationForm'])->name('register.landlord');
Route::post('/signup', [LandlordRegistrationController::class, 'register']);
Route::get('/login-landlord', [LandlordLoginController::class, 'showLoginForm'])->name('login-landlord');
Route::post('/login-landlord', [LandlordLoginController::class, 'login'])->name('login-landlord.post');

// Landlord softdelete
Route::delete('/landlords/{id}', [LandlordRegistrationController::class, 'softDeleteLandlord'])->name('landlord.softDelete');

// Landlord middleware
Route::middleware(['landlord.auth'])->group(function () {
    Route::get('/landlord/dashboard', [LandlordLoginController::class, 'dashboard'])->name('landlord.dashboard');
    Route::post('/logout-landlord', [LandlordLoginController::class, 'logout'])->name('logout-landlord');
    Route::get('/tenant/register', [TenantController::class, 'showRegistrationForm'])->name('tenant.register');
    Route::post('/tenant/register', [TenantController::class, 'register'])->name('tenant.register.submit');
    Route::get('/landlord/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::post('/profile/upload', [ProfileController::class, 'upload'])->name('profile.upload');
    Route::post('/profile/update', [ProfileController::class, 'updateField'])->name('profile.update');

    // New route for showing all tenants
    Route::get('/tenant/show', [TenantController::class, 'showAllTenant'])->name('tenant.show');

    // Add the delete route
    Route::delete('/tenant/show/{tenant_id}', [TenantController::class, 'softDeleteTenant'])->name('tenant.destroy');

    // Update password
    Route::get('/landlord/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('profile.change-password');
    Route::post('/landlord/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password.update');

    // Lease routes
    Route::get('/leases/create', [LeaseController::class, 'create'])->name('leases.create');
    Route::post('/leases', [LeaseController::class, 'store'])->name('leases.store');
    Route::get('/leases', [LeaseController::class, 'index'])->name('leases.index');
    Route::get('/leases/{lease}/edit', [LeaseController::class, 'edit'])->name('leases.edit');
    Route::put('/leases/{lease}', [LeaseController::class, 'update'])->name('leases.update');
    Route::delete('/leases/{lease}', [LeaseController::class, 'softDeleteLease'])->name('leases.destroy');

    // Rent routes
    Route::get('/rent', [RentController::class, 'index'])->name('rent.index');
    Route::get('/rent/create', [RentController::class, 'create'])->name('rent.create');
    Route::post('/rent/store', [RentController::class, 'store'])->name('rent.store');
    Route::put('/rent/{rentPayment}/updateStatus', [RentController::class, 'updateStatus'])->name('rent.updateStatus');

    // Utility Bill routes
    Route::get('/utility/create', [UtilityBillController::class, 'create'])->name('utility.create');
    Route::post('/utility', [UtilityBillController::class, 'store'])->name('utility.store');
    Route::get('/utility', [UtilityBillController::class, 'index'])->name('utility.index');
    Route::put('/utility/{utility_billsPayment}/updateStatus', [UtilityBillController::class, 'updateStatus'])->name('utility.updateStatus');
});
