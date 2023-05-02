<?php

use App\Http\Controllers\API\course\courseController;
use App\Http\Controllers\house_keeping\HousekeepingAdditionalServiceController;
use App\Http\Controllers\house_keeping\HousekeepingCategoryController;
use App\Http\Controllers\API\tutoring\tutoringController;
use App\Http\Controllers\UserController;
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

Route::prefix("tutoring")->group(function () {
    Route::get('/', [tutoringController::class, 'index']);
    Route::post('/', [tutoringController::class, 'store']);
    Route::get('/show/{id}', [tutoringController::class, 'show']);
    Route::put('/{id}', [tutoringController::class, 'update']);
    Route::delete('/{id}', [tutoringController::class, 'destroy']);
});

Route::prefix("course")->group(function () {
    Route::get('/', [courseController::class, 'index']);
    Route::get('/show/{id}', [courseController::class, 'show']);
    Route::post('/', [courseController::class, 'store']);
    Route::put('/{id}', [courseController::class, 'update']);
    Route::delete('/{id}', [courseController::class, 'destroy']);
});
