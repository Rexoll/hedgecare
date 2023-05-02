<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HousekeepingCategoryController;
use App\Http\Controllers\HousekeepingAdditionalServiceController;
use App\Http\Controllers\StreetAddressController;

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

Route::prefix("user")->group(function () {
    Route::post('/register', [UserController::class, "register"]);
});

Route::prefix("housekeeping")->group(function () {
    Route::prefix("categories")->group(function () {
        Route::get("/", [HousekeepingCategoryController::class, "index"]);
    });
    Route::prefix("services")->group(function () {
        Route::get("/", [HousekeepingAdditionalServiceController::class, "index"]);
    });
});

Route::prefix("addresses")->group(function () {
    Route::middleware('cache.headers:public;max_age=2628000;etag')->get("/", [StreetAddressController::class, "index"]);
});