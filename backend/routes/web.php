<?php

use App\Http\Controllers\Controller;
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

// 'laravelVersion' => Application::VERSION,
// 'phpVersion' => PHP_VERSION,

// Route::view('/', '/welcome')->name('welcome');
Route::redirect('/', '/login')->name('welcome');

Route::get('/cadastro-loja', [Controller::class, 'userForm'])->name('userForm');
Route::post('/cadastro-loja', [Controller::class, 'userFormSubmit'])->name('userFormSubmit');

Route::view('/cadastro-enviado', '/user-submitted')->name('userSubmitSuccess');

Route::view('/politica-de-privacidade', '/politica-de-privacidade');
Route::view('/contrato-de-prestacao-de-servicos-speedapp-para-entregadores', '/contrato-entregador');
