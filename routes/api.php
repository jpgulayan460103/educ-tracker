<?php

use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\CompositionController;
use App\Http\Controllers\FundAllocationController;
use App\Http\Controllers\PsgcController;
use App\Http\Controllers\SchoolLevelController;
use App\Http\Controllers\SwadOfficeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::resources([
    // 'family-composition' => CompositionController::class,
    // 'beneficiaries' => BeneficiaryController::class,
    'swad-offices' => SwadOfficeController::class,
    'school-levels' => SchoolLevelController::class,
]);

Route::get('beneficiaries/reports', [BeneficiaryController::class, 'report'])->name('report');
Route::get('fund-allocations/reports', [FundAllocationController::class, 'report'])->name('report.fund-allocation');
Route::get('districts', [SwadOfficeController::class, 'districts'])->name('swad-offices.districts');
