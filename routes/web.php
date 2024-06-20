<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Master\RuleCalculationPointController;
use App\Http\Controllers\User\UserManagementController;
use Illuminate\Support\Facades\Route;

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

Route::get('signin', [AuthController::class, 'signin'])->name('signin');
Route::post('authentication', [AuthController::class, 'authentication'])->name('authentication');
Route::get('signout', [AuthController::class, 'signout'])->name('signout');

/**
 * Home Route
 */
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', function () {
        return view('home');
    })->name('home');
});

/**
 * Super Admin Route Access
 */
Route::group(['middleware' => ['role:owner']], function () {
    /**
     * Route Rule Calculation Point Management Module
     */
    Route::group(['controller' => RuleCalculationPointController::class, 'prefix' => 'rule-calculation-point', 'as' => 'rule-calculation-point.'], function () {
        Route::get('datatable', 'dataTable')->name('dataTable');
    });
    Route::resource('rule-calculation-point', RuleCalculationPointController::class)->parameters(['rule-calculation-point' => 'id']);

    /**
     * Route User Management
     */
    Route::group(['controller' => UserManagementController::class, 'prefix' => 'user-management', 'as' => 'user.'], function () {
        Route::get('datatable', 'dataTable')->name('dataTable');
    });
    Route::resource('user-management', UserManagementController::class)->parameters(['user-management' => 'id']);
});
