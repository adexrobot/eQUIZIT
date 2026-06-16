@extends('layouts.faculty')

@section('title', 'Dashboard')

@section('content')
<h2 class="mb-4">Faculty Dashboard</h2>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Total Quizzes</p>
                        <h3>{{ $stats['total_quizzes'] }}</h3>
                    </div>
                    <i class="bi bi-journal-text fs-2 text-primary"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Active Quizzes</p>
                        <h3>{{ $stats['active_quizzes'] }}</h3>
                    </div>
                    <i class="bi bi-check-circle fs-2 text-success"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Participants</p>
                        <h3>{{ $stats['total_participants'] }}</h3>
                    </div>
                    <i class="bi bi-people fs-2 text-info"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <p class="text-muted mb-1">Average Score</p>
                        <h3>{{ $stats['average_score'] }}%</h3>
                    </div>
                    <i class="bi bi-graph-up fs-2 text-warning"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Recent Quizzes</h5></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Title</th><th>Code</th><th>Status</th><th></th></tr></thead>
                        <tbody>
                        @forelse($recentQuizzes as $quiz)
                            <tr>
                                <td>{{ $quiz->title }}</td>
                                <td><code>{{ $quiz->quiz_code }}</code></td>
                                <td><span class="badge bg-{{ $quiz->status === 'published' ? 'success' : ($quiz->status === 'closed' ? 'secondary' : 'warning') }}">{{ ucfirst($quiz->status) }}</span></td>
                                <td><a href="{{ route('faculty.quizzes.edit', $quiz) }}" class="btn btn-sm btn-outline-primary">Manage</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">No quizzes yet. <a href="{{ route('faculty.quizzes.create') }}">Create one</a></td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Recent Activity</h5></div>
            <div class="card-body">
                @forelse($recentActivities as $log)
                    <div class="d-flex mb-3 pb-3 border-bottom">
                        <i class="bi bi-clock-history text-primary me-2"></i>
                        <div>
                            <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                            <p class="mb-0 small">{{ $log->description }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-muted mb-0">No recent activity.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
