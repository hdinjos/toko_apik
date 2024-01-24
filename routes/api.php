<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\VerifyJwt;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group([

	'prefix' => 'auth'
], function ($router) {
	Route::post('/login', [AuthController::class, 'login']);
	Route::post('/register', [AuthController::class, 'register']);
});

//1 adm, 2 customer
Route::group([
	'middleware' => ['jwt.verify:1,2'],
	'prefix' => 'auth'
], function ($router) {
	Route::post('/logout', [AuthController::class, 'logout']);
	Route::post('/refresh', [AuthController::class, 'refreshToken']);
	Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

Route::group([
	'middleware' => ['jwt.verify:1'],
], function ($router) {
	Route::get('/products', [ProductController::class, 'index']);
	Route::post('/products', [ProductController::class, 'store']);
	Route::get('/products/{productId}', [ProductController::class, 'show']);
});
