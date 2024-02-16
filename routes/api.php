<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\TaskController;






Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('auth/register', [UserController::class, 'register']);
Route::post('auth/login', [UserController::class, 'login']);

    Route::middleware('auth:sanctum')->prefix('tareas')->group(function() {
        Route::post('/', [TaskController::class, 'create']);
        Route::get('/', [TaskController::class, 'list']);
        Route::put('{id}', [TaskController::class, 'update']);
        Route::get('/{id}', [TaskController::class, 'show']);
        Route::delete('/{id}', [TaskController::class, 'remove']);
        Route::post('/{taskid}/assign/{userid}', [TaskController::class, 'assignTaskToUser']);        
    });

    Route::middleware('auth:sanctum')->group(function() {
        Route::get('auth/logout', [UserController::class, 'logout']);
    });