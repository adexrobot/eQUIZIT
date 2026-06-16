<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Answer;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Services\ReportExportService;
use Illuminate\Support\Facades\Auth;

class AnalyticsController extends Controller
{
    public function index()
    {
        $quizzes = Auth::user()->quizzes()
            ->withCount(['attempts' => fn ($q) => $q->whereIn('status', ['submitted', 'graded'])])
            ->latest()
            ->get();

        return view('faculty.analytics.index', compact('quizzes'));
    }

    public function show($id)
    {
        $quiz = Auth::user()->quizzes()->findOrFail($id);

        $attempts = QuizAttempt::with('student')
            ->where('quiz_id', $quiz->id)
            ->whereIn('status', ['submitted', 'graded'])
            ->orderByDesc('date_taken')
            ->get();

        $stats = [
            'highest' => $attempts->max('percentage') ?? 0,
            'lowest' => $attempts->min('percentage') ?? 0,
            'average' => round($attempts->avg('percentage') ?? 0, 2),
            'total' => $attempts->count(),
        ];

        $questionAnalysis = $quiz->questions()->get()->map(function ($question) use ($quiz) {
            $answers = Answer::whereHas('attempt', fn ($q) => $q->where('quiz_id', $quiz->id))
                ->where('question_id', $question->id)
                ->get();

            return [
                'question' => $question,
                'total_answers' => $answers->count(),
                'correct_count' => $answers->where('is_correct', true)->count(),
                'average_score' => round($answers->avg('score') ?? 0, 2),
            ];
        });

        return view('faculty.analytics.show', compact('quiz', 'attempts', 'stats', 'questionAnalysis'));
    }

    public function exportCsv($id, ReportExportService $exportService)
    {
        $quiz = Auth::user()->quizzes()->findOrFail($id);

        return $exportService->exportCsv($quiz);
    }

    public function exportPdf($id, ReportExportService $exportService)
    {
        $quiz = Auth::user()->quizzes()->findOrFail($id);

        return $exportService->exportPdf($quiz);
    }
}
