<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\QuizAttempt;
use App\Services\AiEvaluationService;
use App\Services\GradingService;
use App\Mail\QuizSubmittedMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ExamController extends Controller
{
    public function show(QuizAttempt $attempt)
    {
        $this->authorizeAttempt($attempt);

        $attempt->load(['quiz', 'answers.question']);

        $questions = collect($attempt->question_order)->map(function ($id) use ($attempt) {
            return $attempt->answers->firstWhere('question_id', $id)?->question;
        })->filter();

        return view('student.exam', compact('attempt', 'questions'));
    }

    public function saveAnswer(Request $request, QuizAttempt $attempt)
    {
        $this->authorizeAttempt($attempt);

        if ($attempt->status !== 'in_progress') {
            return response()->json(['success' => false, 'message' => 'Exam already submitted.'], 403);
        }

        $data = $request->validate([
            'question_id' => ['required', 'exists:questions,id'],
            'student_answer' => ['nullable', 'string'],
        ]);

        $answer = $attempt->answers()->where('question_id', $data['question_id'])->firstOrFail();
        $answer->update(['student_answer' => $data['student_answer']]);

        return response()->json(['success' => true, 'message' => 'Answer saved.']);
    }

    public function submit(QuizAttempt $attempt, GradingService $gradingService, AiEvaluationService $aiService)
    {
        $this->authorizeAttempt($attempt);

        if ($attempt->status !== 'in_progress') {
            return redirect()->route('student.result', $attempt);
        }

        $attempt->load('answers.question');

        foreach ($attempt->answers as $answer) {
            if ($answer->question->isObjective()) {
                $gradingService->gradeObjective($answer, $answer->question);
            } else {
                $aiService->evaluateEssay($answer, $answer->question);
            }
        }

        $gradingService->recalculateAttemptScore($attempt->fresh(['answers']));

        $attempt->update([
            'status' => 'graded',
            'submitted_at' => now(),
            'date_taken' => now(),
        ]);

        session()->forget('student_attempt_id');

        try {
            $facultyEmail = $attempt->quiz->faculty->email;
            if ($facultyEmail && config('mail.default')) {
                Mail::to($facultyEmail)->send(new QuizSubmittedMail($attempt->fresh(['quiz', 'student'])));
            }
        } catch (\Throwable $e) {
            Log::warning('Quiz submission email failed', ['message' => $e->getMessage()]);
        }

        return redirect()->route('student.result', $attempt)->with('success', 'Quiz submitted successfully!');
    }

    public function result(QuizAttempt $attempt)
    {
        $attempt->load(['quiz', 'student', 'answers.question']);

        return view('student.result', compact('attempt'));
    }

    protected function authorizeAttempt(QuizAttempt $attempt): void
    {
        $sessionAttemptId = session('student_attempt_id');

        if ($attempt->status === 'in_progress' && $sessionAttemptId != $attempt->id) {
            abort(403, 'Unauthorized exam access.');
        }
    }
}
