@extends('layouts.faculty')

@section('title', 'Edit Question')

@section('content')
<h2 class="mb-4">Edit Question - {{ $quiz->title }}</h2>
<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('faculty.quizzes.questions.update', [$quiz, $question]) }}">
            @csrf @method('PUT')
            @include('faculty.questions._form', ['question' => $question])
            <button type="submit" class="btn btn-primary">Update Question</button>
            <a href="{{ route('faculty.quizzes.edit', $quiz) }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
