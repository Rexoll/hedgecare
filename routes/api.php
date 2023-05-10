<?php

use App\Http\Controllers\course\courseController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\housekeeping\HousekeepingAdditionalServiceController;
use App\Http\Controllers\housekeeping\HousekeepingCategoryController;
use App\Http\Controllers\housekeeping\HousekeepingOrderController;
use App\Http\Controllers\Provider\ProviderController;
use App\Http\Controllers\Skill\SkillController;
use App\Http\Controllers\tutoring\tutoringController;
use App\Http\Controllers\tutoring\tutoringOrderController;
use App\Http\Controllers\user\UserController;
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
    Route::prefix("orders")->group(function () {
        Route::post("/", [HousekeepingOrderController::class, "store"]);
        Route::post("/{order_id}/payWithCard", [HousekeepingOrderController::class, "payWithCard"]);
    });
});

Route::prefix("tutoring")->group(function () {
    Route::get('/', [tutoringController::class, 'index']);
    Route::post('/', [tutoringOrderController::class, 'store']);
    Route::post('/payWithCard/{order_id}', [tutoringOrderController::class, 'payWithCard']);
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

Route::prefix("providers")->group(function () {
    Route::middleware('cache.headers:public;max_age=2628000;etag')->get("/", [ProviderController::class, "index"]);
});

Route::prefix("skills")->group(function () {
    Route::get("/", [SkillController::class, "index"]);
});

Route::prefix("email")->group(function () {
    Route::post("/send", [EmailController::class, "send"]);
    Route::get("/test", [EmailController::class, "test"]);
});