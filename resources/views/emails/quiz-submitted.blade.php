<!DOCTYPE html>
<html>
<body style="font-family: Arial, sans-serif;">
    <h2>Quiz Submission Notification</h2>
    <p>A student has submitted the quiz: <strong>{{ $attempt->quiz->title }}</strong></p>
    <p>
        <strong>Student:</strong> {{ $attempt->student->name }} ({{ $attempt->student->student_id }})<br>
        <strong>Score:</strong> {{ $attempt->score }}/{{ $attempt->max_score }} ({{ $attempt->percentage }}%)<br>
        <strong>Date:</strong> {{ $attempt->date_taken?->format('M d, Y H:i') }}
    </p>
    <p><em>eQUIZMona - Electronic Quiz Management Platform</em></p>
</body>
</html>
