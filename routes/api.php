<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Custom\CustomOrderController;
use App\Http\Controllers\Housekeeping\HousekeepingAdditionalServiceController;
use App\Http\Controllers\Housekeeping\HousekeepingCategoryController;
use App\Http\Controllers\Housekeeping\HousekeepingOrderController;
use App\Http\Controllers\jobBoard\jobBoardController;
use App\Http\Controllers\Maintenance\MaintenanceAdditionalServiceController;
use App\Http\Controllers\Maintenance\MaintenanceCategoryController;
use App\Http\Controllers\Maintenance\MaintenanceOrderController;
use App\Http\Controllers\orderController;
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
        "data" => Auth::user(),
    ], 200);
})->middleware(['auth:sanctum'])->name('verification.verify');

Route::prefix("housekeeping")->group(function () {
    Route::prefix('review')->group(function () {
        Route::put('/{id}', [HousekeepingOrderController::class, 'review']);
    });
    Route::prefix("categories")->group(function () {
        Route::get("/", [HousekeepingCategoryController::class, "index"]);
    });
    Route::prefix("services")->group(function () {
        Route::get("/", [HousekeepingAdditionalServiceController::class, "index"]);
    });
    Route::middleware(['auth:sanctum'])->prefix("orders")->group(function () {
        Route::post("/", [HousekeepingOrderController::class, "store"]);
        Route::put("/update/{id}", [HousekeepingOrderController::class, "updateOrder"]);
        Route::post("/{order_id}/payWithCard", [HousekeepingOrderController::class, "payWithCard"]);
    });
});

Route::prefix("maintenance")->group(function () {
    Route::prefix('review')->group(function () {
        Route::put('/{id}', [MaintenanceOrderController::class, 'review']);
    });
    Route::prefix("categories")->group(function () {
        Route::get("/", [MaintenanceCategoryController::class, "index"]);
    });
    Route::prefix("services")->group(function () {
        Route::get("/", [MaintenanceAdditionalServiceController::class, "index"]);
    });
    Route::middleware(['auth:sanctum'])->prefix("orders")->group(function () {
        Route::post("/", [MaintenanceOrderController::class, "store"]);
        Route::put("/update/{id}", [MaintenanceOrderController::class, "updateOrder"]);
        Route::post("/{order_id}/payWithCard", [MaintenanceOrderController::class, "payWithCard"]);
    });
});

Route::prefix("jobBoard")->group(function () {
    Route::prefix('review')->group(function () {
        Route::put('/{id}', [jobBoardController::class, 'review']);
    });
    Route::middleware(['auth:sanctum'])->prefix("orders")->group(function () {
        Route::get('/getAll', [jobBoardController::class, 'get']);
        Route::get('/search', [jobBoardController::class, 'searchJobBoard']);
        Route::post("/", [jobBoardController::class, "store"]);
        Route::put("/update/{id}", [jobBoardController::class, "updateOrder"]);
        Route::post("/{order_id}/payWithCard", [jobBoardController::class, "payWithCard"]);
        Route::put('/acceptJob/{id}', [jobBoardController::class, 'setAcctive']);
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
    Route::prefix('review')->group(function () {
        Route::put('/{id}', [rentAfriendOrderController::class, 'review']);
    });
    Route::get('/', [rentAfriendCategoryController::class, 'index']);
    Route::prefix("orders")->group(function () {
        Route::post('/', [rentAfriendOrderController::class, 'store']); //create order
        Route::put("/update/{id}", [rentAfriendOrderController::class, "updateOrder"]); //update order
        Route::post('/payWithCard/{order_id}', [rentAfriendOrderController::class, 'payWithCard']); //pay with card
    });
    Route::prefix("categories")->group(function () {
        Route::get('/', [rentAfriendCategoryController::class, 'index']);
    });
    Route::middleware(['auth:sanctum'])->prefix("orders")->group(function () {
        Route::post('/', [rentAfriendOrderController::class, 'store']); //create order
        Route::post('/payWithCard/{order_id}', [rentAfriendOrderController::class, 'payWithCard']); //pay with card
    });
});

Route::prefix("providers")->group(function () {
    Route::middleware('cache.headers:public;max_age=1800;etag')->get("/", [ProviderController::class, "index"]);
    Route::patch("/{id}", [ProviderController::class, "update"]);
    Route::delete("/delete/{id}", [ProviderController::class, "destroy"]);
});

Route::prefix("skills")->group(function () {
    Route::get("/", [SkillController::class, "index"]);
});

Route::prefix("custom")->group(function () {
    Route::prefix('review')->group(function () {
        Route::put('/{id}', [CustomOrderController::class, 'review']);
    });
    Route::middleware(['auth:sanctum'])->prefix("orders")->group(function () {
        Route::post("/", [CustomOrderController::class, "store"]);
        Route::put("/update/{id}", [CustomOrderController::class, "updateOrder"]); //update order
        Route::post("/{order_id}/payWithCard", [CustomOrderController::class, "payWithCard"]);
    });
});

Route::prefix('auth')->group(function () {
    Route::prefix('users')->group(function () {
        Route::middleware('auth:sanctum')->patch('/currentUser', [AuthController::class, 'updateProfile']);
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::middleware('auth:sanctum')->delete('/logout', [AuthController::class, 'logout']);
        Route::middleware('auth:sanctum')->get('/current', [AuthController::class, 'currentUser']);
    });
    Route::prefix('providers')->group(function () {
        Route::post('/register', [AuthController::class, 'provider_register']);
    });
    Route::prefix('admin')->group(function () {
        Route::post('/login', [AuthController::class, 'loginAsAdmin']);
    });
});

Route::middleware(['auth:sanctum'])->prefix('order')->group(function () {
    Route::put('/rating', [orderController::class, 'rating']);
    Route::put('/update', [orderController::class, 'update']);
});

Route::middleware(['auth:sanctum'])->prefix('jobs')->group(function () {
    Route::get('active/currentUser', [AuthController::class, 'getActiveJobUser']);
    Route::get('history/currentUser', [AuthController::class, 'getHistoryJobUser']);
});

//Request a quote
Route::post('request/quote', [orderController::class, 'requestAquot']);
