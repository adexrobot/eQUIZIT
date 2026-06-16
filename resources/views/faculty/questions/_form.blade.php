<div class="mb-3">
    <label class="form-label">Question Type</label>
    <select name="question_type" id="question_type" class="form-select" required>
        @foreach(['multiple_choice' => 'Multiple Choice', 'true_false' => 'True/False', 'identification' => 'Identification', 'essay' => 'Essay'] as $val => $label)
            <option value="{{ $val }}" {{ old('question_type', $question->question_type ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>
</div>
<div class="mb-3">
    <label class="form-label">Question Text</label>
    <textarea name="question_text" class="form-control" rows="3" required>{{ old('question_text', $question->question_text ?? '') }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label">Points</label>
    <input type="number" name="points" class="form-control" step="0.5" min="0.5" value="{{ old('points', $question->points ?? 1) }}" required>
</div>

<div class="mc-fields">
    <div class="row g-2 mb-3">
        <div class="col-md-6"><label class="form-label">Choice A</label><input type="text" name="choice_a" class="form-control" value="{{ old('choice_a', $question->choice_a ?? '') }}"></div>
        <div class="col-md-6"><label class="form-label">Choice B</label><input type="text" name="choice_b" class="form-control" value="{{ old('choice_b', $question->choice_b ?? '') }}"></div>
        <div class="col-md-6"><label class="form-label">Choice C</label><input type="text" name="choice_c" class="form-control" value="{{ old('choice_c', $question->choice_c ?? '') }}"></div>
        <div class="col-md-6"><label class="form-label">Choice D</label><input type="text" name="choice_d" class="form-control" value="{{ old('choice_d', $question->choice_d ?? '') }}"></div>
    </div>
</div>

<input type="hidden" name="correct_answer" id="correct_answer" value="{{ old('correct_answer', $question->correct_answer ?? '') }}">

<div class="mb-3 objective-fields" id="correctAnswerGroup" style="display:none">
    <label class="form-label" id="correctAnswerLabel">Correct Answer</label>
    <input type="text" id="correct_answer_display" class="form-control" value="{{ old('correct_answer', $question->correct_answer ?? '') }}">
    <small class="text-muted id-hint" style="display:none">Use | to separate multiple acceptable keywords</small>
</div>

<div class="tf-fields mb-3" style="display:none">
    <label class="form-label">Correct Answer</label>
    <select id="tf_correct" class="form-select">
        <option value="True" {{ old('correct_answer', $question->correct_answer ?? '') === 'True' ? 'selected' : '' }}>True</option>
        <option value="False" {{ old('correct_answer', $question->correct_answer ?? '') === 'False' ? 'selected' : '' }}>False</option>
    </select>
</div>

<div class="essay-fields mb-3" style="display:none">
    <label class="form-label">Rubric</label>
    <textarea name="rubric" class="form-control" rows="4">{{ old('rubric', $question->rubric ?? 'Evaluate based on relevance, accuracy, completeness, grammar, and critical thinking.') }}</textarea>
</div>

@push('scripts')
<script>
function initQuestionForm() {
    const typeEl = document.getElementById('question_type');
    if (!typeEl) return;

    function syncCorrectAnswer() {
        const hidden = document.getElementById('correct_answer');
        const type = typeEl.value;
        if (type === 'true_false') {
            hidden.value = document.getElementById('tf_correct').value;
        } else if (type !== 'essay') {
            hidden.value = document.getElementById('correct_answer_display').value;
        }
    }

    function toggleQuestionFields() {
        const type = typeEl.value;
        document.querySelectorAll('.mc-fields, .tf-fields, .essay-fields').forEach(el => el.style.display = 'none');
        const correctGroup = document.getElementById('correctAnswerGroup');
        correctGroup.style.display = 'none';
        document.querySelector('.id-hint').style.display = 'none';

        if (type === 'multiple_choice') {
            document.querySelector('.mc-fields').style.display = 'block';
            correctGroup.style.display = 'block';
            document.getElementById('correctAnswerLabel').textContent = 'Correct Answer (A/B/C/D)';
        } else if (type === 'true_false') {
            document.querySelector('.tf-fields').style.display = 'block';
        } else if (type === 'identification') {
            correctGroup.style.display = 'block';
            document.querySelector('.id-hint').style.display = 'block';
            document.getElementById('correctAnswerLabel').textContent = 'Correct Answer';
        } else if (type === 'essay') {
            document.querySelector('.essay-fields').style.display = 'block';
        }
        syncCorrectAnswer();
    }

    typeEl.addEventListener('change', toggleQuestionFields);
    document.getElementById('tf_correct')?.addEventListener('change', syncCorrectAnswer);
    document.getElementById('correct_answer_display')?.addEventListener('input', syncCorrectAnswer);
    document.querySelector('form')?.addEventListener('submit', syncCorrectAnswer);
    toggleQuestionFields();
}
document.addEventListener('DOMContentLoaded', initQuestionForm);
</script>
@endpush
