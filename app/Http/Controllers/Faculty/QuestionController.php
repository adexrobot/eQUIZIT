<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    public function create(Quiz $quiz)
    {
        $this->authorizeQuiz($quiz);

        return view('faculty.questions.create', compact('quiz'));
    }

    public function store(Request $request, Quiz $quiz, ActivityLogService $logger)
    {
        $this->authorizeQuiz($quiz);

        $data = $this->validateQuestion($request);
        $data['order_number'] = ($quiz->questions()->max('order_number') ?? 0) + 1;

        $quiz->questions()->create($data);
        $logger->log('question_added', 'Added question to quiz: ' . $quiz->title);

        return redirect()->route('faculty.quizzes.edit', $quiz)->with('success', 'Question added.');
    }

    public function edit(Quiz $quiz, $question)
    {
        $this->authorizeQuiz($quiz);
        $question = $quiz->questions()->findOrFail($question);

        return view('faculty.questions.edit', compact('quiz', 'question'));
    }

    public function update(Request $request, Quiz $quiz, $question, ActivityLogService $logger)
    {
        $this->authorizeQuiz($quiz);
        $question = $quiz->questions()->findOrFail($question);

        $data = $this->validateQuestion($request);
        $question->update($data);
        $logger->log('question_updated', 'Updated question in quiz: ' . $quiz->title);

        return redirect()->route('faculty.quizzes.edit', $quiz)->with('success', 'Question updated.');
    }

    public function destroy(Quiz $quiz, $question, ActivityLogService $logger)
    {
        $this->authorizeQuiz($quiz);
        $question = $quiz->questions()->findOrFail($question);
        $question->delete();

        $order = 1;
        foreach ($quiz->questions()->orderBy('order_number')->get() as $q) {
            $q->update(['order_number' => $order++]);
        }

        $logger->log('question_deleted', 'Deleted question from quiz: ' . $quiz->title);

        return back()->with('success', 'Question deleted.');
    }

    protected function authorizeQuiz(Quiz $quiz): void
    {
        if ($quiz->faculty_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
    }

    protected function validateQuestion(Request $request): array
    {
        $data = $request->validate([
            'question_text' => ['required', 'string'],
            'question_type' => ['required', 'in:multiple_choice,true_false,identification,essay'],
            'choice_a' => ['nullable', 'string', 'max:500'],
            'choice_b' => ['nullable', 'string', 'max:500'],
            'choice_c' => ['nullable', 'string', 'max:500'],
            'choice_d' => ['nullable', 'string', 'max:500'],
            'correct_answer' => ['nullable', 'string', 'max:500'],
            'points' => ['required', 'numeric', 'min:0.5', 'max:100'],
            'rubric' => ['nullable', 'string'],
        ]);

        if ($data['question_type'] === 'essay') {
            $data['correct_answer'] = null;
            $data['choice_a'] = $data['choice_b'] = $data['choice_c'] = $data['choice_d'] = null;
        }

        return $data;
    }
}
