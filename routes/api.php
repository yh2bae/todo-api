<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TaskController;
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

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['jwt_token', 'cors'])->group(function () {

    Route::prefix('category')->group(function () {
        Route::get('/', [CategoryController::class, 'getAll']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('/{id}', [CategoryController::class, 'getDetail']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'delete']);
    });
    
    Route::prefix('task')->group(function () {
        Route::get('/', [TaskController::class, 'getAll']);
        Route::post('/', [TaskController::class, 'store']);
        Route::get('/{id}', [TaskController::class, 'getDetail']);
        Route::put('/{id}', [TaskController::class, 'update']);
        Route::delete('/{id}', [TaskController::class, 'delete']);
    
        Route::get('/by-category/{category_id}', [TaskController::class, 'getAllByCategory']);
        Route::get('/for/complete', [TaskController::class, 'getAllCompleted']);
        Route::get('/for/today', [TaskController::class, 'getTaskForToday']);
    });

});
