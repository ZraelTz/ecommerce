<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCategoryController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/* Authentication Routes */
Route::group(['prefix' => '/v1/auth'], function ($router) {
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/check-email-exists', [AuthController::class, 'checkEmailExists']);
    Route::group(['middleware' => ['jwt.verify']], function ($router) {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::post('/refresh', [AuthController::class, 'refresh']);
        Route::get('/user-profile', [AuthController::class, 'userProfile']);
    });
});
/* Authentication Routes End */

/* Product Routes */
Route::group(['prefix' => '/v1/products'], function ($router) {
    Route::get('/all', [ProductController::class, 'getProducts']);
    Route::get('', [ProductController::class, 'getPaginatedProducts']);
    Route::get('/{id}', [ProductController::class, 'getProductById']);
    Route::get('/{category}', [ProductController::class, 'getPaginatedProductsByCategory']);
    Route::get('{category}/all', [ProductController::class, 'getProductsByCategory']);

});
/* Product Routes End */

/* Product Category Routes */
Route::group(['prefix' => '/v1/categories'], function ($router) {
    Route::get('/all', [ProductCategoryController::class, 'getCategories']);
});
/* Product Category Routes End */

/* Order Routes */
Route::post('/v1/orders', 'App\Http\Controllers\OrderController@orderProduct');
/* Order Routes End */