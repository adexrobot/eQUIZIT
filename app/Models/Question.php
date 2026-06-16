<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    protected $fillable = [
        'quiz_id',
        'question_text',
        'question_type',
        'choice_a',
        'choice_b',
        'choice_c',
        'choice_d',
        'correct_answer',
        'points',
        'rubric',
        'order_number',
    ];

    protected $casts = [
        'points' => 'decimal:2',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function isObjective(): bool
    {
        return in_array($this->question_type, ['multiple_choice', 'true_false', 'identification']);
    }

    public function isEssay(): bool
    {
        return $this->question_type === 'essay';
    }
}
