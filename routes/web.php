<?php

use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\BansosController;
use App\Http\Controllers\DtksController;
use App\Http\Controllers\AHPController;
use App\Http\Controllers\BordaController;
use App\Http\Controllers\ExampleController;
use App\Http\Controllers\WargaController;
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



Route::group(['namespace' => 'App\Http\Controllers'], function () {
    Route::group(['middleware' => ['guest']], function () {
        /**
         * Login Routes
         */
        Route::get('/login', 'LoginController@show')->name('login.show');
        Route::post('/login', 'LoginController@login')->name('login.perform');
    });

    Route::group(['middleware' => ['auth']], function () {
        /**
         * Logout Routes
         */
        Route::get('/', 'DashboardController@index')->name('admin.dashboard');
        Route::get('/logout', 'LogoutController@perform')->name('logout.perform');

        Route::group([
            'prefix' => 'dtks',
            'as' => 'dtks.',
        ], function () {
            Route::get('/', [DtksController::class, 'index'])->name('index');
            Route::get('/proses', [DtksController::class, 'proses'])->name('proses');
            Route::post('/upload', [DtksController::class, 'uploadDocument'])->name('uploadDocument');

            Route::get('/create', [DtksController::class, 'create'])->name('create');
            Route::get('/edit/{id}', [DtksController::class, 'edit'])->name('edit');
            Route::delete('/destroy/{id}', [DtksController::class, 'destroy'])->name('destroy');
            Route::put('/update/{id}', [DtksController::class, 'update'])->name('update');
            Route::post('/store', [DtksController::class, 'store'])->name('store');
        });

        Route::group([
            'prefix' => 'alternatif',
            'as' => 'alternatif.',
            'middleware' => ['forbidden.rt'],
        ], function () {
            Route::get('/', [AlternatifController::class, 'index'])->name('index');
            Route::get('/proses', [AlternatifController::class, 'proses'])->name('proses');
            Route::get('/edit/{id}', [AlternatifController::class, 'edit'])->name('edit');
            Route::put('/update/{id}', [AlternatifController::class, 'update'])->name('update');
        });

        Route::prefix('warga')->group(function () {
            Route::get('/', [WargaController::class, 'index'])->name('warga.index');
            Route::get('/create', [WargaController::class, 'create'])->name('warga.create');
            Route::get('/edit/{id}', [WargaController::class, 'edit'])->name('warga.edit');
            Route::post('/', [WargaController::class, 'store'])->name('warga.store');
            Route::post('/update/{id}', [WargaController::class, 'update'])->name('warga.update');
            Route::delete('/destroy/{id}', [WargaController::class, 'destroy'])->name('warga.destroy');
            Route::post('/upload', [WargaController::class, 'uploadDocument'])->name('warga.uploadDocument');
        });

        Route::prefix('ahp')->group(function () {
            Route::get('/rt', [AHPController::class, 'rt'])->middleware([
                'forbidden.psm',
            ])->name('rt.index');

            Route::get('/psm', [AHPController::class, 'psm'])->middleware([
                'forbidden.rt',
            ])->name('psm.index');

            Route::get('/kelurahan', [AHPController::class, 'kelurahan'])->middleware([
                'forbidden.rt',
                'forbidden.psm',
            ])->name('kelurahan.index');
        });

        Route::group([
            'prefix' => 'borda',
            'as' => 'borda.',
            'middleware' => ['forbidden.rt'],
        ], function () {
            Route::get('/', [BordaController::class, 'index'])->name('index');
            Route::get('/cetak', [BordaController::class, 'cetak'])->name('cetak');
        });

        Route::group([
            'prefix' => 'bansos',
            'as' => 'bansos.',
        ], function () {
            Route::get('bansos', [BansosController::class, 'index'])->name('index');
        });

        Route::group([
            'prefix' => 'example',
            'as' => 'example.',
        ], function () {
            Route::get('example', [ExampleController::class, 'index'])->name('index');
        });
    });
});
