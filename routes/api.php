<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/register', [\App\Http\Controllers\Api\AuthController::class, 'register']);
Route::post('/login', [\App\Http\Controllers\Api\AuthController::class, 'login']);

// Protected routes (authentication required)
Route::middleware(['auth:sanctum'])->group(function () {
    
    // Auth routes
    Route::get('/user', [\App\Http\Controllers\Api\AuthController::class, 'user']);
    Route::post('/logout', [\App\Http\Controllers\Api\AuthController::class, 'logout']);
    Route::post('/refresh', [\App\Http\Controllers\Api\AuthController::class, 'refreshToken']);

    Route::prefix('admin')->group(function () {
        Route::get('/scholarships', [\App\Http\Controllers\Api\Admin\ScholarshipController::class, 'index']);
        Route::post('/scholarships', [\App\Http\Controllers\Api\Admin\ScholarshipController::class, 'store']);
        Route::put('/scholarships/{id}', [\App\Http\Controllers\Api\Admin\ScholarshipController::class, 'update']);
        Route::delete('/scholarships/{id}', [\App\Http\Controllers\Api\Admin\ScholarshipController::class, 'destroy']);
        Route::get('/scholarships/{id}/budgets', [\App\Http\Controllers\Api\Admin\ScholarshipController::class, 'viewBudgets']);
        Route::post('/scholarships/{id}/budgets', [\App\Http\Controllers\Api\Admin\ScholarshipController::class, 'setBudget']);

        Route::get('/cost-categories', [\App\Http\Controllers\Api\Admin\CostCategoryController::class, 'index']);
        Route::post('/cost-categories', [\App\Http\Controllers\Api\Admin\CostCategoryController::class, 'store']);

        Route::get('/applications', [\App\Http\Controllers\Api\Admin\ApplicationController::class, 'index']);
        Route::post('/applications/{id}/review', [\App\Http\Controllers\Api\Admin\ApplicationController::class, 'review']);
    });

    Route::get('/scholarships', [\App\Http\Controllers\Api\ScholarshipController::class, 'index']);
    
    // Application routes
    Route::post('/applications', [\App\Http\Controllers\Api\ApplicationController::class, 'store']);
    Route::post('/applications/{id}/documents', [\App\Http\Controllers\Api\ApplicationController::class, 'uploadDocuments']);
    Route::get('/my-applications', [\App\Http\Controllers\Api\ApplicationController::class, 'myApplications']);
    Route::get('/applications/{id}', [\App\Http\Controllers\Api\ApplicationController::class, 'show']);
});
