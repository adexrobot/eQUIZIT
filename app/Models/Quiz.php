<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    protected $fillable = [
        'faculty_id',
        'title',
        'subject',
        'course_code',
        'description',
        'quiz_code',
        'duration',
        'max_attempts',
        'available_from',
        'available_until',
        'randomize_questions',
        'status',
    ];

    protected $casts = [
        'available_from' => 'datetime',
        'available_until' => 'datetime',
        'randomize_questions' => 'boolean',
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class)->orderBy('order_number');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }

    public function uploadedFiles(): HasMany
    {
        return $this->hasMany(UploadedFile::class);
    }

    public function totalPoints(): float
    {
        return (float) $this->questions()->sum('points');
    }

    public function isAvailable(): bool
    {
        if ($this->status !== 'published') {
            return false;
        }

        $now = now();

        if ($this->available_from && $now->lt($this->available_from)) {
            return false;
        }

        if ($this->available_until && $now->gt($this->available_until)) {
            return false;
        }

        return true;
    }
}
