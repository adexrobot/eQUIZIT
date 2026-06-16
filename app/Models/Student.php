<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Student extends Model
{
    protected $fillable = [
        'student_id',
        'name',
        'program',
        'year_level',
        'section',
    ];

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
