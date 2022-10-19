<?php

use App\Http\Controllers\Driver\CityController;
use App\Http\Controllers\Driver\DeliveryController;
use App\Http\Controllers\Driver\LoginController;
use App\Http\Controllers\Driver\MetadataController;
use App\Http\Controllers\Driver\ProfileController;
use App\Http\Controllers\Driver\RegisterController;
use App\Http\Controllers\Driver\StateController;
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

Route::group([
    'prefix' => 'driver',
    'as' => 'driver.',
], function () {
    Route::get('/states', [StateController::class, 'index']);

    // TODO: unused after build 68
    Route::get('/cities', [CityController::class, 'index']);

    Route::post('/login/fake', [LoginController::class, 'fake']);

    Route::post('/login/request-code', [LoginController::class, 'requestCode']);
    Route::post('/login/check-code', [LoginController::class, 'checkCode']);

    Route::post('/register/request-code', [RegisterController::class, 'requestCode']);
    Route::post('/register/check-code', [RegisterController::class, 'checkCode']);
    Route::post('/register/finish', [RegisterController::class, 'finish'])->middleware('auth:sanctum');

    Route::get('/profile', [ProfileController::class, 'profile'])->middleware('auth:sanctum');
    Route::post('/profile/pix', [ProfileController::class, 'updatePix'])->middleware('auth:sanctum');
    
    Route::post('/profile/documents', [ProfileController::class, 'storeDocuments'])->middleware('auth:sanctum');
    Route::get('/profile/documents/{document}', [ProfileController::class, 'showDocument'])->name('showDocument');//->middleware('auth:sanctum');

    Route::put('/metadata/status', [MetadataController::class, 'updateStatus'])->middleware('auth:sanctum');
    Route::put('/metadata/location', [MetadataController::class, 'updateLocation'])->middleware('auth:sanctum');
    Route::put('/metadata/device', [MetadataController::class, 'updateDevice'])->middleware('auth:sanctum');

    Route::get('/deliveries', [DeliveryController::class, 'index'])->middleware('auth:sanctum');
    Route::get('/deliveries/today', [DeliveryController::class, 'today'])->middleware('auth:sanctum');
    Route::get('/deliveries/history', [DeliveryController::class, 'history'])->middleware('auth:sanctum');
    Route::get('/deliveries/{delivery}', [DeliveryController::class, 'show'])->middleware('auth:sanctum');

    Route::post('/deliveries/{delivery}/accept', [DeliveryController::class, 'accept'])->middleware('auth:sanctum');
    Route::post('/deliveries/{delivery}/collect', [DeliveryController::class, 'collect'])->middleware('auth:sanctum');
    Route::post('/deliveries/{delivery}/deliver', [DeliveryController::class, 'deliver'])->middleware('auth:sanctum');
    Route::post('/deliveries/{delivery}/confirm', [DeliveryController::class, 'confirm'])->middleware('auth:sanctum');
    // Route::post('/deliveries/{delivery}/returnn', [DeliveryController::class, 'returnn'])->middleware('auth:sanctum');
    Route::post('/deliveries/{delivery}/complete', [DeliveryController::class, 'complete'])->middleware('auth:sanctum');
    Route::post('/deliveries/{delivery}/receive', [DeliveryController::class, 'receive'])->middleware('auth:sanctum');
    Route::post('/deliveries/{delivery}/refuse', [DeliveryController::class, 'refuse'])->middleware('auth:sanctum');
});

