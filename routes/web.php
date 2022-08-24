<?php

use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\CompositionController;
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
Route::get('/encoding', [App\Http\Controllers\HomeController::class, 'encoding'])->name('encoding');

// Route::post('login', 'Auth\LoginController@login');
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::resources([
    'family-composition' => CompositionController::class,
    'beneficiaries' => BeneficiaryController::class,
]);

