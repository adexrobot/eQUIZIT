@extends('layouts.faculty')

@section('title', 'Analytics - ' . $quiz->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2>{{ $quiz->title }}</h2>
        <p class="text-muted mb-0">Analytics & Student Results</p>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('faculty.analytics.export.csv', $quiz) }}" class="btn btn-outline-success btn-sm"><i class="bi bi-file-earmark-spreadsheet"></i> Export CSV</a>
        <a href="{{ route('faculty.analytics.export.pdf', $quiz) }}" class="btn btn-outline-danger btn-sm"><i class="bi bi-file-earmark-pdf"></i> Export PDF</a>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3"><div class="card stat-card border-0 shadow-sm"><div class="card-body"><p class="text-muted mb-1">Highest</p><h3>{{ $stats['highest'] }}%</h3></div></div></div>
    <div class="col-md-3"><div class="card stat-card border-0 shadow-sm"><div class="card-body"><p class="text-muted mb-1">Lowest</p><h3>{{ $stats['lowest'] }}%</h3></div></div></div>
    <div class="col-md-3"><div class="card stat-card border-0 shadow-sm"><div class="card-body"><p class="text-muted mb-1">Average</p><h3>{{ $stats['average'] }}%</h3></div></div></div>
    <div class="col-md-3"><div class="card stat-card border-0 shadow-sm"><div class="card-body"><p class="text-muted mb-1">Total Attempts</p><h3>{{ $stats['total'] }}</h3></div></div></div>
</div>

<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Score Distribution</h5></div>
            <div class="card-body"><canvas id="scoreChart" height="120"></canvas></div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white"><h5 class="mb-0">Question Analysis</h5></div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($questionAnalysis as $i => $qa)
                        <div class="list-group-item">
                            <strong>Q{{ $i + 1 }}</strong>
                            <small class="d-block text-muted">{{ Str::limit($qa['question']->question_text, 60) }}</small>
                            <small>Avg Score: {{ $qa['average_score'] }} | Correct: {{ $qa['correct_count'] }}/{{ $qa['total_answers'] }}</small>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Student Results</h5></div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th><th>Student ID</th><th>Program</th><th>Year</th><th>Section</th><th>Score</th><th>%</th><th>Date</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($attempts as $attempt)
                    <tr>
                        <td>{{ $attempt->student->name }}</td>
                        <td>{{ $attempt->student->student_id }}</td>
                        <td>{{ $attempt->student->program }}</td>
                        <td>{{ $attempt->student->year_level }}</td>
                        <td>{{ $attempt->student->section }}</td>
                        <td>{{ $attempt->score }}/{{ $attempt->max_score }}</td>
                        <td>{{ $attempt->percentage }}%</td>
                        <td>{{ optional($attempt->date_taken)->format('M d, Y H:i') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No submissions yet.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const scores = @json($attempts->pluck('percentage'));
new Chart(document.getElementById('scoreChart'), {
    type: 'bar',
    data: {
        labels: scores.map((_, i) => 'Student ' + (i + 1)),
        datasets: [{ label: 'Score %', data: scores, backgroundColor: '#0d6efd' }]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true, max: 100 } } }
});
</script>
@endpush
