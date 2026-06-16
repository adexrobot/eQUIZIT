@extends('layouts.faculty')

@section('title', 'Edit Quiz')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2>{{ $quiz->title }}</h2>
        <p class="text-muted mb-0">Quiz Code: <code class="fs-5">{{ $quiz->quiz_code }}</code></p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        @if($quiz->status === 'draft')
            <form method="POST" action="{{ route('faculty.quizzes.publish', $quiz) }}">@csrf
                <button class="btn btn-success">Publish</button>
            </form>
        @endif
        @if($quiz->status === 'published')
            <form method="POST" action="{{ route('faculty.quizzes.close', $quiz) }}">@csrf
                <button class="btn btn-secondary">Close Quiz</button>
            </form>
        @endif
        <a href="{{ route('faculty.quizzes.upload.create', $quiz) }}" class="btn btn-outline-primary">Upload File</a>
        <a href="{{ route('faculty.quizzes.questions.create', $quiz) }}" class="btn btn-primary">Add Question</a>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Quiz Settings</h5></div>
            <div class="card-body">
                <form method="POST" action="{{ route('faculty.quizzes.update', $quiz) }}">
                    @csrf @method('PUT')
                    @include('faculty.quizzes._form')
                    <button type="submit" class="btn btn-primary mt-3">Save Changes</button>
                </form>

                @if($quiz->status === 'published')
                <hr>
                <form method="POST" action="{{ route('faculty.quizzes.extend', $quiz) }}" class="mt-3">
                    @csrf
                    <label class="form-label">Extend Deadline</label>
                    <input type="datetime-local" name="available_until" class="form-control mb-2" required>
                    <button class="btn btn-outline-warning btn-sm">Extend</button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between">
                <h5 class="mb-0">Questions ({{ $quiz->questions->count() }})</h5>
                <span class="badge bg-primary">{{ $quiz->totalPoints() }} total points</span>
            </div>
            <div class="card-body">
                @forelse($quiz->questions as $index => $question)
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <strong>Q{{ $index + 1 }}. [{{ str_replace('_', ' ', ucfirst($question->question_type)) }}] ({{ $question->points }} pts)</strong>
                            <div>
                                <a href="{{ route('faculty.quizzes.questions.edit', [$quiz, $question]) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form action="{{ route('faculty.quizzes.questions.destroy', [$quiz, $question]) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this question?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </div>
                        </div>
                        <p class="mb-1 mt-2">{{ $question->question_text }}</p>
                        @if($question->question_type === 'multiple_choice')
                            <small class="text-muted">A) {{ $question->choice_a }} | B) {{ $question->choice_b }} | C) {{ $question->choice_c }} | D) {{ $question->choice_d }} | Answer: {{ $question->correct_answer }}</small>
                        @elseif($question->isObjective())
                            <small class="text-muted">Answer: {{ $question->correct_answer }}</small>
                        @endif
                    </div>
                @empty
                    <p class="text-muted">No questions yet. Add manually or upload a DOCX/PDF file.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
