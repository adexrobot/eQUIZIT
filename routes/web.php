<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Faculty\AnalyticsController;
use App\Http\Controllers\Faculty\DashboardController;
use App\Http\Controllers\Faculty\ProfileController;
use App\Http\Controllers\Faculty\QuestionController;
use App\Http\Controllers\Faculty\QuizController;
use App\Http\Controllers\Faculty\UploadController;
use App\Http\Controllers\Student\ExamController;
use App\Http\Controllers\Student\QuizAccessController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => view('welcome'))->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::prefix('student')->name('student.')->group(function () {
    Route::get('/', [QuizAccessController::class, 'index'])->name('index');
    Route::post('/verify-code', [QuizAccessController::class, 'verifyCode'])->name('verify-code');
    Route::post('/register-info', [QuizAccessController::class, 'registerInfo'])->name('register-info');
    Route::get('/exam/{attempt}', [ExamController::class, 'show'])->name('exam.show');
    Route::post('/exam/{attempt}/save', [ExamController::class, 'saveAnswer'])->name('exam.save');
    Route::post('/exam/{attempt}/submit', [ExamController::class, 'submit'])->name('exam.submit');
    Route::get('/result/{attempt}', [ExamController::class, 'result'])->name('result');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth', 'role:faculty,admin'])->prefix('faculty')->name('faculty.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::resource('quizzes', QuizController::class);
    Route::post('quizzes/{quiz}/publish', [QuizController::class, 'publish'])->name('quizzes.publish');
    Route::post('quizzes/{quiz}/close', [QuizController::class, 'close'])->name('quizzes.close');
    Route::post('quizzes/{quiz}/extend', [QuizController::class, 'extendDeadline'])->name('quizzes.extend');

    Route::get('quizzes/{quiz}/upload', [UploadController::class, 'create'])->name('quizzes.upload.create');
    Route::post('quizzes/{quiz}/upload', [UploadController::class, 'store'])->name('quizzes.upload.store');
    Route::post('quizzes/{quiz}/import-questions', [UploadController::class, 'importQuestions'])->name('quizzes.import-questions');

    Route::resource('quizzes.questions', QuestionController::class)->except(['index', 'show']);

    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('analytics/{quiz}', [AnalyticsController::class, 'show'])->name('analytics.show');
    Route::get('analytics/{quiz}/export/csv', [AnalyticsController::class, 'exportCsv'])->name('analytics.export.csv');
    Route::get('analytics/{quiz}/export/pdf', [AnalyticsController::class, 'exportPdf'])->name('analytics.export.pdf');
});
