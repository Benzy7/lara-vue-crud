<?php

use App\Http\Controllers\CarController;

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

Route::resource('cars', CarController::class);
Route::get('/cars/search/{model}', [CarController::class, 'search']);

//Route::get('/cars', [CarController::class, 'index']);
//Route::post('/cars', [CarController::class, 'store']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
