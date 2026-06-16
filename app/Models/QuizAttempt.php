<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizAttempt extends Model
{
    protected $fillable = [
        'quiz_id',
        'student_id',
        'score',
        'max_score',
        'percentage',
        'status',
        'started_at',
        'submitted_at',
        'date_taken',
        'question_order',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'max_score' => 'decimal:2',
        'percentage' => 'decimal:2',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'date_taken' => 'datetime',
        'question_order' => 'array',
    ];

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'attempt_id');
    }
}
