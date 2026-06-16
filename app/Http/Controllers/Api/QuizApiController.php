<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizApiController extends Controller
{
    public function index()
    {
        $quizzes = Auth::user()->quizzes()
            ->withCount('questions', 'attempts')
            ->latest()
            ->get();

        return response()->json(['data' => $quizzes]);
    }

    public function show(Quiz $quiz)
    {
        if ($quiz->faculty_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $quiz->load('questions');

        return response()->json(['data' => $quiz]);
    }

    public function attempts(Quiz $quiz)
    {
        if ($quiz->faculty_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $attempts = QuizAttempt::with('student')
            ->where('quiz_id', $quiz->id)
            ->whereIn('status', ['submitted', 'graded'])
            ->latest('date_taken')
            ->get();

        return response()->json(['data' => $attempts]);
    }

    public function analytics(Quiz $quiz)
    {
        if ($quiz->faculty_id !== Auth::id() && !Auth::user()->isAdmin()) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $attempts = QuizAttempt::where('quiz_id', $quiz->id)->whereIn('status', ['submitted', 'graded'])->get();

        return response()->json([
            'data' => [
                'highest' => $attempts->max('percentage') ?? 0,
                'lowest' => $attempts->min('percentage') ?? 0,
                'average' => round($attempts->avg('percentage') ?? 0, 2),
                'total_attempts' => $attempts->count(),
            ],
        ]);
    }

    public function verifyCode(Request $request)
    {
        $data = $request->validate(['quiz_code' => ['required', 'string']]);

        $quiz = Quiz::where('quiz_code', strtoupper(trim($data['quiz_code'])))->first();

        if (!$quiz || !$quiz->isAvailable()) {
            return response()->json(['valid' => false, 'message' => 'Quiz not found or unavailable.'], 404);
        }

        return response()->json([
            'valid' => true,
            'data' => [
                'title' => $quiz->title,
                'subject' => $quiz->subject,
                'duration' => $quiz->duration,
                'quiz_code' => $quiz->quiz_code,
            ],
        ]);
    }
}
