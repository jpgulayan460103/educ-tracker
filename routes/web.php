<?php

use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\CompositionController;
use App\Http\Controllers\FundAllocationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
Route::get('/encoded-beneficiaries', [App\Http\Controllers\HomeController::class, 'beneficiaries'])->name('beneficiaries');
Route::get('/encoding/{uuid?}', [App\Http\Controllers\HomeController::class, 'encoding'])->name('encoding');
Route::get('/users', [App\Http\Controllers\HomeController::class, 'users'])->name('users');

// Route::post('login', 'Auth\LoginController@login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/change-password', [App\Http\Controllers\HomeController::class, 'changePassword'])->name('changePassword');
Route::get('/fund-allocation', [App\Http\Controllers\HomeController::class, 'allocations'])->name('allocations');


Route::group(['prefix' => '/data'], function () {
    Route::put('/users/{id}/reset-password', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    Route::get('/family-composition/{uuid}/uuid', [CompositionController::class, 'showUuid'])->name('family-composition.uuid');
    Route::post('/export/beneficiaries/{type}', [BeneficiaryController::class, 'export'])->name('export.beneficiary');
    Route::resources([
        'family-composition' => CompositionController::class,
        'beneficiaries' => BeneficiaryController::class,
        'users' => UserController::class,
        'fund-allocations' => FundAllocationController::class,
    ]);
});
