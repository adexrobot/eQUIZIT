<?php

namespace App\Services;

use App\Models\Quiz;

class QuizCodeGenerator
{
    public function generate(string $courseCode): string
    {
        $year = date('Y');
        $prefix = 'QUIZ' . $year . strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $courseCode));
        $suffix = strtoupper(substr(uniqid(), -4));

        $code = $prefix . $suffix;

        while (Quiz::where('quiz_code', $code)->exists()) {
            $suffix = strtoupper(substr(uniqid(), -4));
            $code = $prefix . $suffix;
        }

        return $code;
    }
}
