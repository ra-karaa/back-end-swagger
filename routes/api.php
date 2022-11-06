<?php

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

Route::patch('setting', [App\Http\Controllers\API\V1Controller::class, 'updateSetting']);
Route::post('employees', [App\Http\Controllers\API\V1Controller::class, 'storeEmployees']);
Route::post('overtimes', [App\Http\Controllers\API\V1Controller::class, 'storeOvertimes']);
Route::get('overtimes-pay/calculate/{date?}', [App\Http\Controllers\API\V1Controller::class, 'overtimePay']);

