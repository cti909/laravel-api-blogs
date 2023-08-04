<?php

use App\Http\Controllers\APIs\AuthController;
use App\Http\Controllers\APIs\CategoryController;
use App\Http\Controllers\APIs\CommentController;
use App\Http\Controllers\APIs\LikeController;
use App\Http\Controllers\APIs\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

/**
 * Auth
 */
Route::group(['prefix' => 'auth'], function () {
    // Route::get('/me', [AuthController::class, 'me']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    // Route::post('/sendOtpEmail', [AuthController::class, 'sendOtpEmail']);
    // Route::post('/emailVerification', [AuthController::class, 'emailVerification']);
});

/**
 * User
 */
// .....

/**
 * Post
 */
Route::group(['prefix' => 'posts'], function () {
    Route::get('/image/{imageName}', [PostController::class, 'getImageUrl']);
    Route::get('/', [PostController::class, 'index']);
    Route::get('/{post_id}', [PostController::class, 'show']);
    Route::middleware([
        'auth:api',
        'jwt.auth',
    ])->group(function () {
        Route::post('/', [PostController::class, 'store']);
        Route::put('/{post_id}', [PostController::class, 'update']);
        Route::delete('/{post_id}', [PostController::class, 'destroy']);
    });
});

/**
 * Comment
 */
Route::group(['prefix' => 'comments'], function () {
    Route::get('/', [CommentController::class, 'index']);
    Route::get('/{comment_id}', [CommentController::class, 'show']);
    Route::middleware([
        'auth:api',
        'jwt.auth',
    ])->group(function () {
        Route::post('/', [CommentController::class, 'store']);
        Route::put('/{comment_id}', [CommentController::class, 'update']);
        Route::delete('/{comment_id}', [CommentController::class, 'destroy']);
    });
});

/**
 * Category
 */
Route::group(['prefix' => 'categories'], function () {
    Route::get('/', [CategoryController::class, 'index']);
});

/**
 * Like
 */
Route::group([
    'prefix' => 'likes',
    'middleware' => [
        'api',
        'jwt.auth',
    ]
], function () {
    Route::post('/add', [LikeController::class, 'likeObjectAdd']);
    Route::post('/del', [LikeController::class, 'likeObjectDel']);
});
