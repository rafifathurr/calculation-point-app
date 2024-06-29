<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Guest\GuestController;
use App\Http\Controllers\Master\CustomerController;
use App\Http\Controllers\Master\MenuController;
use App\Http\Controllers\Master\PointGradeController;
use App\Http\Controllers\Master\PromoPointController;
use App\Http\Controllers\Master\RuleCalculationPointController;
use App\Http\Controllers\User\UserManagementController;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('home');
    } else {
        return redirect()->route('guest.home');
    }
});

/**
 * Home Guest Route
 */
Route::group(['middleware' => 'guest'], function () {
    /**
     * Route Guest Module
     */
    Route::group(['prefix' => 'guest', 'as' => 'guest.'], function () {
        /**
         * Home Route
         */
        Route::get('home', function () {
            return view('guest.home');
        })->name('home');

        /**
         * Route Guest Module
         */
        Route::group(['controller' => GuestController::class], function () {
            Route::get('check', 'check')->name('check');
            Route::get('get-data', 'getData')->name('getData');
        });
    });
});

/**
 * Home Auth Route
 */
Route::group(['middleware' => 'auth'], function () {
    Route::get('home', function () {
        return view('home');
    })->name('home');
});

/**
 * Owner Route Access
 */
Route::group(['middleware' => ['role:owner']], function () {
    /**
     * Route Menu Module
     */
    Route::group(['controller' => PromoPointController::class, 'prefix' => 'promo-point', 'as' => 'promo-point.'], function () {
        Route::get('datatable', 'dataTable')->name('dataTable');
    });
    Route::resource('promo-point', PromoPointController::class)->parameters(['promo-point' => 'id']);

    /**
     * Route Menu Module
     */
    Route::group(['controller' => MenuController::class, 'prefix' => 'menu', 'as' => 'menu.'], function () {
        Route::get('datatable', 'dataTable')->name('dataTable');
    });
    Route::resource('menu', MenuController::class)->parameters(['menu' => 'id']);

    /**
     * Route Rule Calculation Point Module
     */
    Route::group(['controller' => RuleCalculationPointController::class, 'prefix' => 'rule-calculation-point', 'as' => 'rule-calculation-point.'], function () {
        Route::get('datatable', 'dataTable')->name('dataTable');
        Route::get('date-configuration', 'dateConfiguration')->name('dateConfiguration');
    });
    Route::resource('rule-calculation-point', RuleCalculationPointController::class)->parameters(['rule-calculation-point' => 'id']);

    /**
     * Route Point Grade Module
     */
    Route::group(['controller' => PointGradeController::class, 'prefix' => 'point-grade', 'as' => 'point-grade.'], function () {
        Route::get('datatable', 'dataTable')->name('dataTable');
    });
    Route::resource('point-grade', PointGradeController::class)->parameters(['point-grade' => 'id']);

    /**
     * Route User Management Module
     */
    Route::group(['controller' => UserManagementController::class, 'prefix' => 'user-management', 'as' => 'user-management.'], function () {
        Route::get('datatable', 'dataTable')->name('dataTable');
    });
    Route::resource('user-management', UserManagementController::class)->parameters(['user-management' => 'id']);
});

/**
 * Owner Route Access
 */
Route::group(['middleware' => ['role:cashier']], function () {
    /**
     * Route Customer Module
     */
    Route::group(['controller' => CustomerController::class, 'prefix' => 'customer', 'as' => 'customer.'], function () {
        Route::get('datatable', 'dataTable')->name('dataTable');
    });
    Route::resource('customer', CustomerController::class)->parameters(['customer' => 'id']);
});
