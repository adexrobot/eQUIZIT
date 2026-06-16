@extends('layouts.app')

@section('title', 'Quiz Result')

@section('body')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg text-center">
                <div class="card-body p-5">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <i class="bi bi-trophy-fill display-3 text-warning"></i>
                    <h2 class="mt-3">Quiz Completed!</h2>
                    <p class="text-muted">{{ $attempt->quiz->title }}</p>

                    <div class="display-3 fw-bold text-primary my-4">{{ $attempt->score }}/{{ $attempt->max_score }}</div>
                    <h4>{{ $attempt->percentage }}%</h4>

                    <hr class="my-4">

                    <h5 class="text-start mb-3">Detailed Feedback</h5>
                    @foreach($attempt->answers as $i => $answer)
                        <div class="text-start border rounded p-3 mb-3">
                            <strong>Q{{ $i + 1 }}: {{ Str::limit($answer->question->question_text, 80) }}</strong>
                            <p class="mb-1 small">Score: {{ $answer->score ?? 0 }}/{{ $answer->question->points }}</p>
                            @if($answer->question->isEssay() && $answer->ai_feedback)
                                @php $feedback = json_decode($answer->ai_feedback, true); @endphp
                                @if(!empty($feedback['summary']))
                                    <p class="mb-2">{{ $feedback['summary'] }}</p>
                                @endif
                                @if(!empty($feedback['strengths']))
                                    <p class="mb-1"><strong>Strengths:</strong></p>
                                    <ul>@foreach($feedback['strengths'] as $s)<li>{{ $s }}</li>@endforeach</ul>
                                @endif
                                @if(!empty($feedback['improvements']))
                                    <p class="mb-1"><strong>Areas for Improvement:</strong></p>
                                    <ul>@foreach($feedback['improvements'] as $s)<li>{{ $s }}</li>@endforeach</ul>
                                @endif
                                @if(!empty($feedback['recommended_learning']))
                                    <p class="mb-1"><strong>Recommended Learning:</strong></p>
                                    <ul>@foreach($feedback['recommended_learning'] as $s)<li>{{ $s }}</li>@endforeach</ul>
                                @endif
                                @if($answer->ai_probability_human !== null)
                                    <div class="alert alert-info small mt-2 mb-0">
                                        <strong>Writing Pattern Analysis (Indicator Only):</strong><br>
                                        Human Written: {{ $answer->ai_probability_human }}% |
                                        AI Generated: {{ $answer->ai_probability_generated }}%<br>
                                        <em>This is an indicator only and not definitive proof of AI use.</em>
                                    </div>
                                @endif
                            @elseif($answer->question->isObjective())
                                <p class="small mb-0">
                                    Your answer: {{ $answer->student_answer ?? '—' }} |
                                    {{ $answer->is_correct ? '✓ Correct' : '✗ Incorrect' }}
                                </p>
                            @endif
                        </div>
                    @endforeach

                    <a href="{{ route('student.index') }}" class="btn btn-primary mt-3">Take Another Quiz</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
