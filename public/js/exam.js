document.addEventListener('DOMContentLoaded', function () {
    if (typeof examConfig === 'undefined') return;

    let currentIndex = 0;
    const panels = document.querySelectorAll('.question-panel');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const submitBtn = document.getElementById('submitBtn');
    const saveStatus = document.getElementById('saveStatus');
    const timerEl = document.getElementById('examTimer');

    function showQuestion(index) {
        panels.forEach((p, i) => p.style.display = i === index ? 'block' : 'none');
        document.querySelectorAll('.nav-q').forEach((btn, i) => {
            btn.classList.toggle('active', i === index);
        });
        prevBtn.disabled = index === 0;
        nextBtn.style.display = index < panels.length - 1 ? 'inline-block' : 'none';
        submitBtn.style.display = index === panels.length - 1 ? 'inline-block' : 'none';
        currentIndex = index;
    }

    prevBtn?.addEventListener('click', () => showQuestion(currentIndex - 1));
    nextBtn?.addEventListener('click', () => showQuestion(currentIndex + 1));
    document.querySelectorAll('.nav-q').forEach(btn => {
        btn.addEventListener('click', () => showQuestion(parseInt(btn.dataset.index)));
    });

    function saveAnswer(questionId, answer) {
        fetch(examConfig.saveUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': examConfig.csrf,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ question_id: questionId, student_answer: answer }),
        }).then(r => r.json()).then(data => {
            if (saveStatus) {
                saveStatus.innerHTML = data.success
                    ? '<i class="bi bi-check-circle text-success"></i> Saved'
                    : '<i class="bi bi-exclamation-circle text-danger"></i> Save failed';
            }
        }).catch(() => {
            if (saveStatus) saveStatus.innerHTML = '<i class="bi bi-exclamation-circle text-danger"></i> Save failed';
        });
    }

    document.querySelectorAll('.auto-save').forEach(input => {
        input.addEventListener('change', function () {
            saveAnswer(this.dataset.questionId, this.value);
        });
    });

    let textTimeout;
    document.querySelectorAll('.auto-save-text').forEach(input => {
        input.addEventListener('input', function () {
            clearTimeout(textTimeout);
            const qid = this.dataset.questionId;
            const val = this.value;
            textTimeout = setTimeout(() => saveAnswer(qid, val), 800);
        });
    });

    if (timerEl) {
        const duration = parseInt(timerEl.dataset.duration) * 60;
        const started = parseInt(timerEl.dataset.started);
        let remaining = duration - (Math.floor(Date.now() / 1000) - started);

        function updateTimer() {
            if (remaining <= 0) {
                timerEl.textContent = '00:00';
                timerEl.classList.add('text-warning');
                document.getElementById('confirmSubmit')?.click();
                return;
            }
            const m = Math.floor(remaining / 60);
            const s = remaining % 60;
            timerEl.textContent = String(m).padStart(2, '0') + ':' + String(s).padStart(2, '0');
            if (remaining <= 300) timerEl.classList.add('text-warning');
            remaining--;
        }

        updateTimer();
        setInterval(updateTimer, 1000);
    }

    document.getElementById('confirmSubmit')?.addEventListener('click', function () {
        document.getElementById('examForm').submit();
    });
});
