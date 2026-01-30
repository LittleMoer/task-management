<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;


Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    Route::post('/dashboard/create', [TaskController::class, 'createTask']);
    Route::get('/dashboard', [TaskController::class, 'getUserTasks']);
    Route::post('/dashboard/edit', [TaskController::class, 'editTask']);
    Route::post('/dashboard/delete', [TaskController::class, 'deleteTask']);
});