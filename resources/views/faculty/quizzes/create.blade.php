@extends('layouts.faculty')

@section('title', 'Create Quiz')

@section('content')
<h2 class="mb-4">Create Quiz</h2>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('faculty.quizzes.store') }}">
            @csrf
            @include('faculty.quizzes._form')
            <button type="submit" class="btn btn-primary">Create Quiz</button>
        </form>
    </div>
</div>
@endsection
