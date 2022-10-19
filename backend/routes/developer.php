<?php

use App\Http\Controllers\Developer\AdminController;
use App\Http\Controllers\Developer\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Developer\ChartController;
use App\Http\Controllers\Developer\CityController;
use App\Http\Controllers\Developer\DashboardController;
use App\Http\Controllers\Developer\DeliveryController;
use App\Http\Controllers\Developer\DriverController;
use App\Http\Controllers\Developer\MapController;
use App\Http\Controllers\Developer\PaymentController;
use App\Http\Controllers\Developer\ProfileController;
use App\Http\Controllers\Developer\ReportController;
use App\Http\Controllers\Developer\StateController;
use App\Http\Controllers\Developer\UserController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'developer',
    'as' => 'developer.',
], function () {

        Route::group(['middleware' => ['guest:web', 'guest:admin', 'guest:developer']], function () {
            Route::get('login', [AuthenticatedSessionController::class, 'create'])
                ->name('login');

            Route::post('/login', [AuthenticatedSessionController::class, 'store']);
        });

        Route::group(['middleware' => ['auth:developer']], function () {
            Route::get('logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
            
            Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
                ->name('logout');

            Route::get('profile', [ProfileController::class, 'show'])
                ->name('profile.show');

            // Route::put('/user/password', [PasswordController::class, 'update'])
            //     ->middleware(['auth:'.config('fortify.guard')])
            //     ->name('user-password.update');

            Route::get('dashboard', [DashboardController::class, 'index'])
                ->name('dashboard');

            Route::get('deliveries', [DeliveryController::class, 'index'])
                ->name('deliveries');

            Route::get('deliveries/{delivery}', [DeliveryController::class, 'show'])
                ->name('deliveries.show');

            Route::get('deliveries/{delivery}/receipt', [DeliveryController::class, 'receipt'])
                ->name('deliveries.show.receipt');

            Route::post('deliveries/{delivery}/redispatch', [DeliveryController::class, 'redispatch'])
                ->name('deliveries.redispatch');

            Route::get('deliveries/{delivery}/edit', [DeliveryController::class, 'edit'])
                ->name('deliveries.edit');

            Route::put('deliveries/{delivery}/edit/{step}', [DeliveryController::class, 'update'])
                ->name('deliveries.update');

            Route::get('request-delivery', [DeliveryController::class, 'create'])
                ->name('request-delivery');

            Route::post('request-delivery/check', [DeliveryController::class, 'check'])
                ->name('request-delivery.check');
            
            Route::post('request-delivery', [DeliveryController::class, 'store'])
                ->name('request-delivery.store');

            Route::post('request-lastmile-delivery', [DeliveryController::class, 'storeLastmile'])
                ->name('request-delivery.store.lastmile');
    
            Route::get('deliveries-history', [DeliveryController::class, 'history'])
                ->name('deliveries.history');

            Route::get('map', [MapController::class, 'index'])->name('map');
            Route::get('charts', [ChartController::class, 'index'])->name('charts');

            Route::put('admins/{admin}/updatePassword', [AdminController::class, 'updatePassword'])->name('admins.updatePassword');
            Route::put('admins/{admin}/updateCities', [AdminController::class, 'updateCities'])->name('admins.updateCities');
            Route::resource('admins', AdminController::class);

            Route::put('drivers/{driver}/approve', [DriverController::class, 'approve'])->name('drivers.approve');
            Route::put('drivers/{driver}/reject', [DriverController::class, 'reject'])->name('drivers.reject');
            Route::put('drivers/{driver}/unreject', [DriverController::class, 'unreject'])->name('drivers.unreject');

            Route::put('drivers/{driver}/updateBank', [DriverController::class, 'updateBank'])->name('drivers.updateBank');
            Route::put('drivers/{driver}/updateTransport', [DriverController::class, 'updateTransport'])->name('drivers.updateTransport');
            Route::put('drivers/{driver}/updateCloud', [DriverController::class, 'updateCloud'])->name('drivers.updateCloud');

            Route::post('drivers/{driver}/documents', [DriverController::class, 'storeDocuments'])->name('drivers.storeDocuments');
            Route::delete('drivers/{driver}/documents/{document}', [DriverController::class, 'deleteDocument'])->name('drivers.deleteDocument');
            Route::get('drivers/{driver}/documents/{document}', [DriverController::class, 'showDocument'])->name('drivers.showDocument');

            Route::post('drivers/{driver}/ban', [DriverController::class, 'ban'])->name('drivers.ban');
            Route::post('drivers/{driver}/unban', [DriverController::class, 'unban'])->name('drivers.unban');

            Route::post('drivers/{driver}/status', [DriverController::class, 'updateStatus'])->name('drivers.updateStatus');

            Route::resource('drivers', DriverController::class);

            Route::put('users/{user}/updatePassword', [UserController::class, 'updatePassword'])->name('users.updatePassword');
            Route::put('users/{user}/updateOptions', [UserController::class, 'updateOptions'])->name('users.updateOptions');
            Route::put('users/{user}/updateCities', [UserController::class, 'updateCities'])->name('users.updateCities');
            Route::put('users/{user}/updateDrivers', [UserController::class, 'updateDrivers'])->name('users.updateDrivers');
            Route::put('users/{user}/updateAddress', [UserController::class, 'updateAddress'])->name('users.updateAddress');

            Route::post('users/{user}/delivery-settings/area', [UserController::class, 'updateArea'])
                ->name('users.area.store');
                
            Route::post('users/{user}/delivery-settings/area/radiuses', [UserController::class, 'updateRadiuses'])
                ->name('users.area.radiuses.store');

            Route::post('users/{user}/delivery-settings/area/chargeOptions', [UserController::class, 'updateChargeOptions'])
                ->name('users.area.chargeOptions.store');

            Route::put('users/{user}/updateReport', [UserController::class, 'updateReport'])->name('users.updateReport');
            Route::get('users/{user}/reports', [UserController::class, 'reports'])->name('users.reports');
            Route::get('users/{user}/reports/download', [UserController::class, 'downloadReport'])->name('users.reports.download');

            Route::resource('users', UserController::class);

            Route::resource('cities', CityController::class);
            Route::resource('states', StateController::class);

            // reports
            Route::get('reports', [ReportController::class, 'index'])->name('reports');
            Route::get('reports/download', [ReportController::class, 'download'])->name('reports.download');

            Route::post('reports', [ReportController::class, 'generate'])->name('reports.generate');
        });
});
