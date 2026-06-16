<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label">Quiz Title</label>
        <input type="text" name="title" class="form-control" value="{{ old('title', $quiz->title ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Course Code</label>
        <input type="text" name="course_code" class="form-control" value="{{ old('course_code', $quiz->course_code ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Subject Name</label>
        <input type="text" name="subject" class="form-control" value="{{ old('subject', $quiz->subject ?? '') }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Time Limit (minutes)</label>
        <input type="number" name="duration" class="form-control" value="{{ old('duration', $quiz->duration ?? 60) }}" min="1" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Max Attempts</label>
        <input type="number" name="max_attempts" class="form-control" value="{{ old('max_attempts', $quiz->max_attempts ?? 1) }}" min="1" required>
    </div>
    <div class="col-12">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3">{{ old('description', $quiz->description ?? '') }}</textarea>
    </div>
    <div class="col-md-6">
        <label class="form-label">Available From</label>
        <input type="datetime-local" name="available_from" class="form-control" value="{{ old('available_from', isset($quiz) && $quiz->available_from ? $quiz->available_from->format('Y-m-d\TH:i') : '') }}">
    </div>
    <div class="col-md-6">
        <label class="form-label">Available Until</label>
        <input type="datetime-local" name="available_until" class="form-control" value="{{ old('available_until', isset($quiz) && $quiz->available_until ? $quiz->available_until->format('Y-m-d\TH:i') : '') }}">
    </div>
    <div class="col-12">
        <div class="form-check">
            <input type="checkbox" name="randomize_questions" value="1" class="form-check-input" id="randomize" {{ old('randomize_questions', $quiz->randomize_questions ?? true) ? 'checked' : '' }}>
            <label class="form-check-label" for="randomize">Randomize question order for students</label>
        </div>
    </div>
</div>
