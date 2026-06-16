@extends('layouts.faculty')

@section('title', 'Analytics')

@section('content')
<h2 class="mb-4">Student Results & Analytics</h2>

<div class="row g-3">
@forelse($quizzes as $quiz)
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <h5>{{ $quiz->title }}</h5>
                <p class="text-muted small mb-2">{{ $quiz->quiz_code }}</p>
                <p class="mb-3"><span class="badge bg-primary">{{ $quiz->attempts_count }} submissions</span></p>
                <a href="{{ route('faculty.analytics.show', $quiz) }}" class="btn btn-outline-primary btn-sm">View Analytics</a>
            </div>
        </div>
    </div>
@empty
    <div class="col-12"><p class="text-muted">No quizzes with results yet.</p></div>
@endforelse
</div>
@endsection
