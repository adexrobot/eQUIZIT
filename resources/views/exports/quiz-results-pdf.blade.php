<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quiz Results - {{ $quiz->title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { font-size: 18px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background: #f0f0f0; }
        .stats { margin: 15px 0; }
    </style>
</head>
<body>
    <h1>eQUIZMona - Quiz Results Report</h1>
    <p><strong>Quiz:</strong> {{ $quiz->title }} ({{ $quiz->quiz_code }})</p>
    <p><strong>Subject:</strong> {{ $quiz->subject }} | <strong>Course:</strong> {{ $quiz->course_code }}</p>

    <div class="stats">
        <strong>Highest:</strong> {{ $stats['highest'] }}% |
        <strong>Lowest:</strong> {{ $stats['lowest'] }}% |
        <strong>Average:</strong> {{ $stats['average'] }}%
    </div>

    <table>
        <thead>
            <tr>
                <th>Name</th><th>Student ID</th><th>Program</th><th>Year</th><th>Section</th><th>Score</th><th>%</th><th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attempts as $attempt)
            <tr>
                <td>{{ $attempt->student->name }}</td>
                <td>{{ $attempt->student->student_id }}</td>
                <td>{{ $attempt->student->program }}</td>
                <td>{{ $attempt->student->year_level }}</td>
                <td>{{ $attempt->student->section }}</td>
                <td>{{ $attempt->score }}/{{ $attempt->max_score }}</td>
                <td>{{ $attempt->percentage }}%</td>
                <td>{{ optional($attempt->date_taken)->format('Y-m-d H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
