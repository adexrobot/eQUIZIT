@extends('layouts.faculty')

@section('title', 'Admin Dashboard')

@section('content')
<h2 class="mb-4">Admin Dashboard</h2>

<div class="row g-4 mb-4">
    <div class="col-md-3"><div class="card stat-card border-0 shadow-sm"><div class="card-body"><p class="text-muted mb-1">Faculty</p><h3>{{ $stats['total_faculty'] }}</h3></div></div></div>
    <div class="col-md-3"><div class="card stat-card border-0 shadow-sm"><div class="card-body"><p class="text-muted mb-1">Quizzes</p><h3>{{ $stats['total_quizzes'] }}</h3></div></div></div>
    <div class="col-md-3"><div class="card stat-card border-0 shadow-sm"><div class="card-body"><p class="text-muted mb-1">Attempts</p><h3>{{ $stats['total_attempts'] }}</h3></div></div></div>
    <div class="col-md-3"><div class="card stat-card border-0 shadow-sm"><div class="card-body"><p class="text-muted mb-1">Avg Score</p><h3>{{ $stats['average_score'] }}%</h3></div></div></div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">System Activity Logs</h5></div>
    <div class="card-body p-0">
        <table class="table mb-0">
            <thead><tr><th>User</th><th>Action</th><th>Description</th><th>IP</th><th>Date</th></tr></thead>
            <tbody>
            @foreach($recentActivities as $log)
                <tr>
                    <td>{{ $log->user->name ?? 'System' }}</td>
                    <td>{{ $log->action }}</td>
                    <td>{{ $log->description }}</td>
                    <td>{{ $log->ip_address }}</td>
                    <td>{{ $log->created_at->format('M d, Y H:i') }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
