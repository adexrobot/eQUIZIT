<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Services\QuizCodeGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    public function index()
    {
        $quizzes = Auth::user()->quizzes()->withCount('questions', 'attempts')->latest()->paginate(10);

        return view('faculty.quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        return view('faculty.quizzes.create');
    }

    public function store(Request $request, QuizCodeGenerator $codeGenerator, ActivityLogService $logger)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'course_code' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'duration' => ['required', 'integer', 'min:1', 'max:480'],
            'max_attempts' => ['required', 'integer', 'min:1', 'max:10'],
            'available_from' => ['nullable', 'date'],
            'available_until' => ['nullable', 'date', 'after_or_equal:available_from'],
            'randomize_questions' => ['nullable', 'boolean'],
        ]);

        $quiz = Auth::user()->quizzes()->create([
            ...$data,
            'quiz_code' => $codeGenerator->generate($data['course_code']),
            'randomize_questions' => $request->boolean('randomize_questions', true),
            'status' => 'draft',
        ]);

        $logger->log('quiz_created', 'Created quiz: ' . $quiz->title);

        return redirect()->route('faculty.quizzes.edit', $quiz)->with('success', 'Quiz created. Add questions before publishing.');
    }

    public function show($id)
    {
        $quiz = Auth::user()->quizzes()->with('questions')->findOrFail($id);

        return view('faculty.quizzes.show', compact('quiz'));
    }

    public function edit($id)
    {
        $quiz = Auth::user()->quizzes()->with('questions')->findOrFail($id);

        return view('faculty.quizzes.edit', compact('quiz'));
    }

    public function update(Request $request, $id, ActivityLogService $logger)
    {
        $quiz = Auth::user()->quizzes()->findOrFail($id);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'course_code' => ['required', 'string', 'max:50'],
            'description' => ['nullable', 'string'],
            'duration' => ['required', 'integer', 'min:1', 'max:480'],
            'max_attempts' => ['required', 'integer', 'min:1', 'max:10'],
            'available_from' => ['nullable', 'date'],
            'available_until' => ['nullable', 'date', 'after_or_equal:available_from'],
            'randomize_questions' => ['nullable', 'boolean'],
        ]);

        $quiz->update([
            ...$data,
            'randomize_questions' => $request->boolean('randomize_questions', true),
        ]);

        $logger->log('quiz_updated', 'Updated quiz: ' . $quiz->title);

        return back()->with('success', 'Quiz updated successfully.');
    }

    public function destroy($id, ActivityLogService $logger)
    {
        $quiz = Auth::user()->quizzes()->findOrFail($id);
        $title = $quiz->title;
        $quiz->delete();

        $logger->log('quiz_deleted', 'Deleted quiz: ' . $title);

        return redirect()->route('faculty.quizzes.index')->with('success', 'Quiz deleted.');
    }

    public function publish($id, ActivityLogService $logger)
    {
        $quiz = Auth::user()->quizzes()->withCount('questions')->findOrFail($id);

        if ($quiz->questions_count === 0) {
            return back()->with('error', 'Add at least one question before publishing.');
        }

        $quiz->update(['status' => 'published']);
        $logger->log('quiz_published', 'Published quiz: ' . $quiz->title);

        return back()->with('success', 'Quiz published. Code: ' . $quiz->quiz_code);
    }

    public function close($id, ActivityLogService $logger)
    {
        $quiz = Auth::user()->quizzes()->findOrFail($id);
        $quiz->update(['status' => 'closed']);
        $logger->log('quiz_closed', 'Closed quiz: ' . $quiz->title);

        return back()->with('success', 'Quiz closed.');
    }

    public function extendDeadline(Request $request, $id, ActivityLogService $logger)
    {
        $quiz = Auth::user()->quizzes()->findOrFail($id);

        $data = $request->validate([
            'available_until' => ['required', 'date', 'after:now'],
        ]);

        $quiz->update($data);
        $logger->log('quiz_extended', 'Extended deadline for: ' . $quiz->title);

        return back()->with('success', 'Deadline extended.');
    }
}
