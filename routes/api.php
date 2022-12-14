<?php

use App\Http\Controllers\ExchangeController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/exchange', [ExchangeController::class, "index"]);
Route::get('/currencies', [ExchangeController::class, "getAllCurrencies"]);
Route::get('/currency/{currencyName}', [ExchangeController::class, "getCurrency"]);
Route::get('/count', [ExchangeController::class, "countCurrencies"]);
