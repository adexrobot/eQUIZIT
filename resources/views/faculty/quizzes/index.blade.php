@extends('layouts.faculty')

@section('title', 'My Quizzes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>My Quizzes</h2>
    <div>
        <a href="{{ route('faculty.quizzes.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Create Quiz</a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Subject</th>
                        <th>Quiz Code</th>
                        <th>Questions</th>
                        <th>Attempts</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($quizzes as $quiz)
                    <tr>
                        <td>{{ $quiz->title }}</td>
                        <td>{{ $quiz->subject }}</td>
                        <td><code>{{ $quiz->quiz_code }}</code></td>
                        <td>{{ $quiz->questions_count }}</td>
                        <td>{{ $quiz->attempts_count }}</td>
                        <td><span class="badge bg-{{ $quiz->status === 'published' ? 'success' : ($quiz->status === 'closed' ? 'secondary' : 'warning') }}">{{ ucfirst($quiz->status) }}</span></td>
                        <td>
                            <a href="{{ route('faculty.quizzes.edit', $quiz) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                            <a href="{{ route('faculty.analytics.show', $quiz) }}" class="btn btn-sm btn-outline-info">Results</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No quizzes found.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
{{ $quizzes->links() }}
@endsection
