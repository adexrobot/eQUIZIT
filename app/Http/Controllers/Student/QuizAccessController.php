<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\Student;
use Illuminate\Http\Request;

class QuizAccessController extends Controller
{
    public function index()
    {
        return view('student.index');
    }

    public function verifyCode(Request $request)
    {
        $data = $request->validate([
            'quiz_code' => ['required', 'string', 'max:50'],
        ]);

        $quiz = Quiz::where('quiz_code', strtoupper(trim($data['quiz_code'])))->first();

        if (!$quiz) {
            return back()->withErrors(['quiz_code' => 'Invalid quiz code.'])->withInput();
        }

        if (!$quiz->isAvailable()) {
            return back()->withErrors(['quiz_code' => 'This quiz is not currently available.'])->withInput();
        }

        session(['pending_quiz_id' => $quiz->id]);

        return view('student.register-info', compact('quiz'));
    }

    public function registerInfo(Request $request)
    {
        $quizId = session('pending_quiz_id');

        if (!$quizId) {
            return redirect()->route('student.index')->with('error', 'Please enter a quiz code first.');
        }

        $quiz = Quiz::findOrFail($quizId);

        if (!$quiz->isAvailable()) {
            return redirect()->route('student.index')->with('error', 'Quiz is no longer available.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'student_id' => ['required', 'string', 'max:50'],
            'program' => ['required', 'string', 'max:255'],
            'year_level' => ['required', 'string', 'max:50'],
            'section' => ['required', 'string', 'max:50'],
        ]);

        $attemptCount = QuizAttempt::where('quiz_id', $quiz->id)
            ->whereHas('student', fn ($q) => $q->where('student_id', $data['student_id']))
            ->whereIn('status', ['submitted', 'graded', 'in_progress'])
            ->count();

        if ($attemptCount >= $quiz->max_attempts) {
            return back()->withErrors(['student_id' => 'Maximum attempts reached for this quiz.'])->withInput();
        }

        $student = Student::firstOrCreate(
            ['student_id' => $data['student_id'], 'name' => $data['name']],
            [
                'program' => $data['program'],
                'year_level' => $data['year_level'],
                'section' => $data['section'],
            ]
        );

        $student->update([
            'program' => $data['program'],
            'year_level' => $data['year_level'],
            'section' => $data['section'],
        ]);

        $questionIds = $quiz->questions()->pluck('id')->toArray();
        if ($quiz->randomize_questions) {
            shuffle($questionIds);
        }

        $attempt = QuizAttempt::create([
            'quiz_id' => $quiz->id,
            'student_id' => $student->id,
            'max_score' => $quiz->totalPoints(),
            'status' => 'in_progress',
            'started_at' => now(),
            'question_order' => $questionIds,
        ]);

        foreach ($questionIds as $questionId) {
            $attempt->answers()->create(['question_id' => $questionId]);
        }

        session([
            'student_attempt_id' => $attempt->id,
            'pending_quiz_id' => null,
        ]);

        return redirect()->route('student.exam.show', $attempt);
    }
}
