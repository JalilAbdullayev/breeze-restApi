<?php

use App\Http\Controllers\Api\V1\ArticleController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->prefix('v1')->group(function() {
    Route::apiResource('articles', ArticleController::class);
    Route::get('authors/{user}', [ArticleController::class, 'show']);
});
