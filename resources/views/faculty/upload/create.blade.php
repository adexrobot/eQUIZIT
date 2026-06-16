@extends('layouts.faculty')

@section('title', 'Upload Quiz')

@section('content')
<h2 class="mb-4">Upload Quiz File - {{ $quiz->title }}</h2>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('faculty.quizzes.upload.store', $quiz) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Upload DOCX or PDF</label>
                        <input type="file" name="quiz_file" class="form-control" accept=".pdf,.docx" required>
                        <small class="text-muted">Max 10MB. Supported: Multiple Choice, True/False, Identification, Essay</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Extract Questions</button>
                    <a href="{{ route('faculty.quizzes.edit', $quiz) }}" class="btn btn-outline-secondary">Back</a>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        @php $parsed = session('parsed_questions_' . $quiz->id, []); @endphp
        @if(!empty($parsed))
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between">
                <h5 class="mb-0">Extracted Questions ({{ count($parsed) }})</h5>
                <span class="text-muted small">Edit before importing</span>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('faculty.quizzes.import-questions', $quiz) }}">
                    @csrf
                    @foreach($parsed as $i => $q)
                        <div class="border rounded p-3 mb-3">
                            <input type="hidden" name="questions[{{ $i }}][question_type]" value="{{ $q['question_type'] }}">
                            <div class="mb-2">
                                <label class="form-label small">Question {{ $i + 1 }}</label>
                                <textarea name="questions[{{ $i }}][question_text]" class="form-control" rows="2">{{ $q['question_text'] }}</textarea>
                            </div>
                            <div class="row g-2">
                                <div class="col-md-3">
                                    <input type="number" name="questions[{{ $i }}][points]" class="form-control form-control-sm" value="{{ $q['points'] ?? 1 }}" step="0.5">
                                </div>
                                @if($q['question_type'] === 'multiple_choice')
                                    <div class="col-md-9">
                                        <input type="text" name="questions[{{ $i }}][choice_a]" class="form-control form-control-sm mb-1" placeholder="A" value="{{ $q['choice_a'] ?? '' }}">
                                        <input type="text" name="questions[{{ $i }}][choice_b]" class="form-control form-control-sm mb-1" placeholder="B" value="{{ $q['choice_b'] ?? '' }}">
                                        <input type="text" name="questions[{{ $i }}][choice_c]" class="form-control form-control-sm mb-1" placeholder="C" value="{{ $q['choice_c'] ?? '' }}">
                                        <input type="text" name="questions[{{ $i }}][choice_d]" class="form-control form-control-sm mb-1" placeholder="D" value="{{ $q['choice_d'] ?? '' }}">
                                        <input type="text" name="questions[{{ $i }}][correct_answer]" class="form-control form-control-sm" placeholder="Answer" value="{{ $q['correct_answer'] ?? 'A' }}">
                                    </div>
                                @else
                                    <div class="col-md-9">
                                        <input type="text" name="questions[{{ $i }}][correct_answer]" class="form-control form-control-sm" placeholder="Correct answer" value="{{ $q['correct_answer'] ?? '' }}">
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                    <button type="submit" class="btn btn-success">Import All Questions</button>
                </form>
            </div>
        </div>
        @else
        <div class="card border-0 shadow-sm">
            <div class="card-body text-muted">
                Upload a file to extract questions. Extracted questions will appear here for review before importing.
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
