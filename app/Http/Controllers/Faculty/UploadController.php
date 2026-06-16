<?php

namespace App\Http\Controllers\Faculty;

use App\Http\Controllers\Controller;
use App\Models\Quiz;
use App\Models\UploadedFile;
use App\Services\ActivityLogService;
use App\Services\QuizParserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UploadController extends Controller
{
    public function create(Quiz $quiz)
    {
        $this->authorizeQuiz($quiz);

        return view('faculty.upload.create', compact('quiz'));
    }

    public function store(Request $request, Quiz $quiz, QuizParserService $parser, ActivityLogService $logger)
    {
        $this->authorizeQuiz($quiz);

        $request->validate([
            'quiz_file' => ['required', 'file', 'mimes:pdf,docx', 'max:10240'],
        ]);

        $file = $request->file('quiz_file');
        $extension = $file->getClientOriginalExtension();
        $path = $file->store('quiz-uploads', 'local');

        UploadedFile::create([
            'quiz_id' => $quiz->id,
            'filename' => $file->getClientOriginalName(),
            'filepath' => $path,
            'file_type' => $extension,
        ]);

        $questions = $parser->parse(Storage::path($path), $extension);
        session(['parsed_questions_' . $quiz->id => $questions]);

        $logger->log('quiz_uploaded', 'Uploaded quiz file for: ' . $quiz->title);

        return redirect()->route('faculty.quizzes.upload.create', $quiz)
            ->with('success', count($questions) . ' questions extracted. Review and import.');
    }

    public function importQuestions(Request $request, Quiz $quiz, ActivityLogService $logger)
    {
        $this->authorizeQuiz($quiz);

        $questions = $request->input('questions', session('parsed_questions_' . $quiz->id, []));

        if (empty($questions)) {
            return back()->with('error', 'No questions to import.');
        }

        $order = ($quiz->questions()->max('order_number') ?? 0) + 1;

        foreach ($questions as $q) {
            $quiz->questions()->create([
                'question_text' => $q['question_text'],
                'question_type' => $q['question_type'],
                'choice_a' => $q['choice_a'] ?? null,
                'choice_b' => $q['choice_b'] ?? null,
                'choice_c' => $q['choice_c'] ?? null,
                'choice_d' => $q['choice_d'] ?? null,
                'correct_answer' => $q['correct_answer'] ?? null,
                'points' => $q['points'] ?? 1,
                'rubric' => $q['rubric'] ?? null,
                'order_number' => $order++,
            ]);
        }

        session()->forget('parsed_questions_' . $quiz->id);
        $logger->log('questions_imported', 'Imported questions to: ' . $quiz->title);

        return redirect()->route('faculty.quizzes.edit', $quiz)->with('success', 'Questions imported successfully.');
    }

    protected function authorizeQuiz(Quiz $quiz): void
    {
        if ($quiz->faculty_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }
    }
}
