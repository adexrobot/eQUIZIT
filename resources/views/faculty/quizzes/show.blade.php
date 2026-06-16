@extends('layouts.faculty')

@section('title', $quiz->title)

@section('content')
<h2 class="mb-4">{{ $quiz->title }}</h2>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <p><strong>Quiz Code:</strong> <code>{{ $quiz->quiz_code }}</code></p>
        <p><strong>Status:</strong> {{ ucfirst($quiz->status) }}</p>
        <p><strong>Questions:</strong> {{ $quiz->questions->count() }}</p>
        <a href="{{ route('faculty.quizzes.edit', $quiz) }}" class="btn btn-primary">Manage Quiz</a>
    </div>
</div>
@endsection
