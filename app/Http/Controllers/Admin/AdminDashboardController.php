<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_faculty' => User::where('role', 'faculty')->count(),
            'total_quizzes' => Quiz::count(),
            'total_attempts' => QuizAttempt::whereIn('status', ['submitted', 'graded'])->count(),
            'average_score' => round(QuizAttempt::whereIn('status', ['submitted', 'graded'])->avg('percentage') ?? 0, 2),
        ];

        $recentActivities = ActivityLog::with('user')->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentActivities'));
    }
}
