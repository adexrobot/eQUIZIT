@extends('layouts.app')

@section('title', $attempt->quiz->title)

@section('body')
<div class="exam-header bg-primary text-white py-3 sticky-top">
    <div class="container-fluid d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <strong>{{ $attempt->quiz->title }}</strong>
            <small class="d-block">{{ $attempt->student->name }} ({{ $attempt->student->student_id }})</small>
        </div>
        <div class="text-end">
            <div id="examTimer" class="fs-4 fw-bold" data-duration="{{ $attempt->quiz->duration }}" data-started="{{ $attempt->started_at->timestamp }}">--:--</div>
            <small>Time Remaining</small>
        </div>
    </div>
</div>

<div class="container-fluid py-4">
    <form id="examForm" method="POST" action="{{ route('student.exam.submit', $attempt) }}">
        @csrf
        <div class="row g-4">
            <div class="col-lg-9">
                @foreach($questions as $index => $question)
                    @php $answer = $attempt->answers->firstWhere('question_id', $question->id); @endphp
                    <div class="card border-0 shadow-sm mb-4 question-panel" id="question-{{ $index }}" style="{{ $index > 0 ? 'display:none' : '' }}">
                        <div class="card-header bg-white d-flex justify-content-between">
                            <span>Question {{ $index + 1 }} of {{ $questions->count() }}</span>
                            <span class="badge bg-secondary">{{ $question->points }} pts</span>
                        </div>
                        <div class="card-body">
                            <p class="fs-5 mb-4">{{ $question->question_text }}</p>

                            @if($question->question_type === 'multiple_choice')
                                @foreach(['A' => $question->choice_a, 'B' => $question->choice_b, 'C' => $question->choice_c, 'D' => $question->choice_d] as $letter => $choice)
                                    @if($choice)
                                    <div class="form-check mb-2 p-3 border rounded answer-option">
                                        <input class="form-check-input auto-save" type="radio" name="display_{{ $question->id }}" id="q{{ $question->id }}_{{ $letter }}"
                                            value="{{ $letter }}" data-question-id="{{ $question->id }}"
                                            {{ $answer && strtoupper($answer->student_answer) === $letter ? 'checked' : '' }}>
                                        <label class="form-check-label" for="q{{ $question->id }}_{{ $letter }}"><strong>{{ $letter }}.</strong> {{ $choice }}</label>
                                    </div>
                                    @endif
                                @endforeach
                            @elseif($question->question_type === 'true_false')
                                @foreach(['True', 'False'] as $opt)
                                <div class="form-check mb-2 p-3 border rounded">
                                    <input class="form-check-input auto-save" type="radio" name="display_{{ $question->id }}" value="{{ $opt }}"
                                        data-question-id="{{ $question->id }}" {{ $answer && $answer->student_answer === $opt ? 'checked' : '' }}>
                                    <label class="form-check-label">{{ $opt }}</label>
                                </div>
                                @endforeach
                            @elseif($question->question_type === 'identification')
                                <input type="text" class="form-control form-control-lg auto-save-text" data-question-id="{{ $question->id }}"
                                    value="{{ $answer->student_answer ?? '' }}" placeholder="Type your answer">
                            @elseif($question->question_type === 'essay')
                                <textarea class="form-control auto-save-text" rows="8" data-question-id="{{ $question->id }}" placeholder="Write your essay answer here...">{{ $answer->student_answer ?? '' }}</textarea>
                            @endif
                        </div>
                    </div>
                @endforeach

                <div class="d-flex justify-content-between">
                    <button type="button" class="btn btn-outline-secondary" id="prevBtn" disabled>Previous</button>
                    <button type="button" class="btn btn-primary" id="nextBtn">Next</button>
                    <button type="button" class="btn btn-success" id="submitBtn" style="display:none" data-bs-toggle="modal" data-bs-target="#submitModal">Submit Quiz</button>
                </div>
            </div>

            <div class="col-lg-3">
                <div class="card border-0 shadow-sm sticky-top" style="top:80px">
                    <div class="card-header bg-white"><strong>Navigation</strong></div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-2" id="questionNav">
                            @foreach($questions as $index => $question)
                                <button type="button" class="btn btn-sm btn-outline-primary nav-q {{ $index === 0 ? 'active' : '' }}" data-index="{{ $index }}">{{ $index + 1 }}</button>
                            @endforeach
                        </div>
                        <div class="mt-3">
                            <small class="text-muted" id="saveStatus"><i class="bi bi-check-circle text-success"></i> Auto-save enabled</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="modal fade" id="submitModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Submit Quiz?</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">Are you sure you want to submit? You cannot change answers after submission.</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmSubmit">Yes, Submit</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const examConfig = {
    attemptId: {{ $attempt->id }},
    saveUrl: '{{ route('student.exam.save', $attempt) }}',
    submitUrl: '{{ route('student.exam.submit', $attempt) }}',
    csrf: '{{ csrf_token() }}',
    totalQuestions: {{ $questions->count() }}
};
</script>
<script src="{{ asset('js/exam.js') }}"></script>
@endpush
