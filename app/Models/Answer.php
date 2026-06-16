<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    protected $fillable = [
        'attempt_id',
        'question_id',
        'student_answer',
        'score',
        'ai_feedback',
        'ai_probability_human',
        'ai_probability_generated',
        'is_correct',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'ai_probability_human' => 'decimal:2',
        'ai_probability_generated' => 'decimal:2',
        'is_correct' => 'boolean',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(QuizAttempt::class, 'attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
