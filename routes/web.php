<?php

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

Route::get('/', [App\Http\Controllers\WelcomeController::class, 'welcome'])->name('welcome');

Route::resource('urls', App\Http\Controllers\UrlController::class)->only(['index', 'store', 'show']);

Route::post('/urls/{id}/checks', [App\Http\Controllers\UrlCheckController::class, 'store'])
    ->name('urls.checks.store');
