<?php

use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\DeliveryController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\MapController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'admin',
    'as' => 'admin.',
], function () {

        Route::group(['middleware' => ['guest:web', 'guest:admin', 'guest:developer']], function () {
            Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

            Route::post('/login', [AuthenticatedSessionController::class, 'store']);
        });

        Route::group(['middleware' => ['auth:admin']], function () {
            Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');

            Route::get('profile', [ProfileController::class, 'show'])
                ->name('profile.show');

            // Route::put('/user/password', [PasswordController::class, 'update'])
            //     ->middleware(['auth:'.config('fortify.guard')])
            //     ->name('user-password.update');

            Route::get('deliveries', [DeliveryController::class, 'index'])
                ->name('deliveries');

            Route::get('deliveries/{delivery}', [DeliveryController::class, 'show'])
                ->middleware('can:view,delivery')
                ->name('deliveries.show');

            Route::put('deliveries/{delivery}/updateDriver', [DeliveryController::class, 'updateDriver'])
                ->name('deliveries.updateDriver');

            Route::post('deliveries/{delivery}/redispatch', [DeliveryController::class, 'redispatch'])
                ->name('deliveries.redispatch');
    
            Route::get('deliveries-history', [DeliveryController::class, 'history'])
                ->name('deliveries.history');

            Route::get('map', [MapController::class, 'index'])->name('map');

            Route::get('drivers', [DriverController::class, 'index'])
                ->name('drivers.index');

            Route::get('drivers/{driver}', [DriverController::class, 'show'])
                ->middleware('can:view,driver')
                ->name('drivers.show');

            Route::post('drivers/{driver}/status', [DriverController::class, 'updateStatus'])
                ->middleware('can:view,driver')
                ->name('drivers.updateStatus');

            Route::get('users', [UserController::class, 'index'])
                ->name('users.index');

            Route::get('users/{user}', [UserController::class, 'show'])
                ->middleware('can:view,user')
                ->name('users.show');
        });
});
