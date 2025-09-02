<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\TaskController;
use App\Http\Controllers\API\ProjectController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::apiResource('users', UserController::class);
Route::get('users/{id}/projects', [UserController::class, 'ShowWithProjects']);

Route::apiResource('projects', ProjectController::class);

Route::apiResource('tasks', TaskController::class);
Route::get('tasks/{id}/status', [TaskController::class, 'ShowWithStatus']);

Route::get('projects/{id}/tasks', [ProjectController::class, 'ShowWithTasks']);