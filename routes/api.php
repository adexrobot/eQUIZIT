<?php

use App\Http\Controllers\Api\QuizApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('/health', fn () => response()->json(['status' => 'ok', 'app' => 'eQUIZMona']));

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/quizzes', [QuizApiController::class, 'index']);
        Route::get('/quizzes/{quiz}', [QuizApiController::class, 'show']);
        Route::get('/quizzes/{quiz}/attempts', [QuizApiController::class, 'attempts']);
        Route::get('/quizzes/{quiz}/analytics', [QuizApiController::class, 'analytics']);
    });

    Route::post('/quiz/verify', [QuizApiController::class, 'verifyCode']);
});
