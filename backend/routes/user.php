<?php

use App\Http\Controllers\User\AreaController;
use App\Http\Controllers\User\DeliveryController;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'user',
    'as' => 'user.',
    'middleware' => ['auth:web']
], function () {
        Route::get('deliveries', [DeliveryController::class, 'index'])
            ->name('deliveries');

        Route::get('deliveries/{delivery}', [DeliveryController::class, 'show'])
            ->middleware('can:view,delivery')
            ->name('deliveries.show');

        Route::get('request-delivery', [DeliveryController::class, 'create'])
            ->name('request-delivery');
        
        Route::post('request-delivery', [DeliveryController::class, 'store'])
            ->name('request-delivery.store'); 

        Route::post('request-lastmile-delivery', [DeliveryController::class, 'storeLastmile'])
            ->name('request-delivery.store.lastmile');

        Route::post('request-delivery/check', [DeliveryController::class, 'check'])
            ->name('request-delivery.check');

        Route::get('deliveries-history', [DeliveryController::class, 'history'])
            ->name('deliveries.history');

        // 
        Route::get('delivery-settings', [AreaController::class, 'index'])
            ->name('area');

        Route::post('delivery-settings/area/chargeOptions', [AreaController::class, 'updateChargeOptions'])
            ->name('area.chargeOptions.store');
});
