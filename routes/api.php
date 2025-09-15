<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\ProjectController;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['ensure.api.token'])->group(function () {
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::get('users/{id}/projects', [UserController::class, 'ShowWithProjects']);

    Route::apiResource('projects', ProjectController::class);
    Route::get('projects/{id}/tasks', [ProjectController::class, 'ShowWithTasks']);

    Route::apiResource('tasks', TaskController::class);
});

// Route::middleware(['auth.sanctum'])->group(function () {
//     Route::get('users/{id}/projects', [UserController::class, 'ShowWithProjects']);

//     Route::apiResource('projects', ProjectController::class);
//     Route::get('projects/{id}/tasks', [ProjectController::class, 'ShowWithTasks']);

//     Route::apiResource('tasks', TaskController::class);
//     Route::get('tasks/{id}/status', [TaskController::class, 'ShowWithStatus']);
// });


