<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacultyProfile extends Model
{
    protected $fillable = [
        'user_id',
        'faculty_id',
        'department',
        'program',
        'contact_number',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
