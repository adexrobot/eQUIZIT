@extends('layouts.faculty')

@section('title', 'Add Question')

@section('content')
<h2 class="mb-4">Add Question - {{ $quiz->title }}</h2>
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('faculty.quizzes.questions.store', $quiz) }}">
            @csrf
            @include('faculty.questions._form')
            <button type="submit" class="btn btn-primary">Add Question</button>
            <a href="{{ route('faculty.quizzes.edit', $quiz) }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
