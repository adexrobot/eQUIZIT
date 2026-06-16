<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\QuizAttempt;
use Barryvdh\DomPDF\Facade\Pdf;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportService
{
    public function exportCsv(Quiz $quiz): StreamedResponse
    {
        $attempts = QuizAttempt::with('student')
            ->where('quiz_id', $quiz->id)
            ->whereIn('status', ['submitted', 'graded'])
            ->orderByDesc('date_taken')
            ->get();

        $filename = 'quiz-results-' . $quiz->quiz_code . '.csv';

        return response()->streamDownload(function () use ($attempts) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Student Name', 'Student ID', 'Program', 'Year Level', 'Section', 'Score', 'Max Score', 'Percentage', 'Date Taken']);

            foreach ($attempts as $attempt) {
                fputcsv($handle, [
                    $attempt->student->name,
                    $attempt->student->student_id,
                    $attempt->student->program,
                    $attempt->student->year_level,
                    $attempt->student->section,
                    $attempt->score,
                    $attempt->max_score,
                    $attempt->percentage . '%',
                    optional($attempt->date_taken)->format('Y-m-d H:i'),
                ]);
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    public function exportPdf(Quiz $quiz)
    {
        $attempts = QuizAttempt::with('student')
            ->where('quiz_id', $quiz->id)
            ->whereIn('status', ['submitted', 'graded'])
            ->orderByDesc('percentage')
            ->get();

        $stats = [
            'highest' => $attempts->max('percentage') ?? 0,
            'lowest' => $attempts->min('percentage') ?? 0,
            'average' => round($attempts->avg('percentage') ?? 0, 2),
        ];

        return Pdf::loadView('exports.quiz-results-pdf', compact('quiz', 'attempts', 'stats'))
            ->download('quiz-results-' . $quiz->quiz_code . '.pdf');
    }
}
