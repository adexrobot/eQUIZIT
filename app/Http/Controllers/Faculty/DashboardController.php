<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $quizIds = Quiz::where('faculty_id', $userId)->pluck('id');

        $stats = [
            'total_quizzes' => $quizIds->count(),
            'active_quizzes' => Quiz::where('faculty_id', $userId)->where('status', 'published')->count(),
            'total_participants' => QuizAttempt::whereIn('quiz_id', $quizIds)->distinct('student_id')->count('student_id'),
            'average_score' => round(QuizAttempt::whereIn('quiz_id', $quizIds)->whereIn('status', ['submitted', 'graded'])->avg('percentage') ?? 0, 2),
        ];

        $recentQuizzes = Quiz::where('faculty_id', $userId)->latest()->take(5)->get();
        $recentActivities = ActivityLog::where('user_id', $userId)->latest()->take(8)->get();

        return view('faculty.dashboard', compact('stats', 'recentQuizzes', 'recentActivities'));
    }
}
