<?php

use App\Http\Controllers\Custom\CustomOrderController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Housekeeping\HousekeepingAdditionalServiceController;
use App\Http\Controllers\Housekeeping\HousekeepingCategoryController;
use App\Http\Controllers\Housekeeping\HousekeepingOrderController;
use App\Http\Controllers\Provider\ProviderController;
use App\Http\Controllers\rentAfriend\rentAfriendCategoryController;
use App\Http\Controllers\rentAfriend\rentAfriendOrderController;
use App\Http\Controllers\Skill\SkillController;
use App\Http\Controllers\tutoring\tutoringController;
use App\Http\Controllers\tutoring\tutoringOrderController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
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

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return response()->json([
        "message" => "success verify email",
        "data" => Auth::user()
    ], 200);
})->middleware(['auth:sanctum'])->name('verification.verify');

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

Route::prefix("rentAfriend")->group(function () {
    Route::get('/', [rentAfriendCategoryController::class, 'index']);
    Route::prefix("orders")->group(function () {
        Route::post('/', [rentAfriendOrderController::class, 'store']); //create order
        Route::post('/payWithCard/{order_id}', [rentAfriendOrderController::class, 'payWithCard']); //pay with card
    });
    Route::prefix("categories")->group(function () {
        Route::get('/', [rentAfriendCategoryController::class, 'index']);
    });
});

Route::prefix("providers")->group(function () {
    Route::middleware('cache.headers:public;max_age=2628000;etag')->get("/", [ProviderController::class, "index"]);
});

Route::prefix("skills")->group(function () {
    Route::get("/", [SkillController::class, "index"]);
});

Route::prefix("custom")->group(function () {
    Route::prefix("orders")->group(function () {
        Route::post("/", [CustomOrderController::class, "store"]);
        Route::post("/{order_id}/payWithCard", [CustomOrderController::class, "payWithCard"]);
    });
});


Route::prefix('auth')->group(function () {
    Route::prefix('users')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
    });
    Route::prefix('providers')->group(function () {
        Route::post('/register', [AuthController::class, 'provider_register']);
        Route::post('/login', [AuthController::class, 'provider_login']);
    });
});
