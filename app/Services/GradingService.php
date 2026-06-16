<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Question;
use App\Models\QuizAttempt;

class GradingService
{
    public function gradeObjective(Answer $answer, Question $question): Answer
    {
        $studentAnswer = trim((string) $answer->student_answer);
        $correctAnswer = trim((string) $question->correct_answer);
        $isCorrect = false;

        switch ($question->question_type) {
            case 'multiple_choice':
                $isCorrect = strtoupper($studentAnswer) === strtoupper($correctAnswer);
                break;
            case 'true_false':
                $isCorrect = strtolower($studentAnswer) === strtolower($correctAnswer);
                break;
            case 'identification':
                $keywords = array_filter(array_map('trim', explode('|', $correctAnswer)));
                if (empty($keywords)) {
                    $isCorrect = strcasecmp($studentAnswer, $correctAnswer) === 0;
                } else {
                    $normalized = strtolower($studentAnswer);
                    $isCorrect = true;
                    foreach ($keywords as $keyword) {
                        if (stripos($normalized, strtolower($keyword)) === false) {
                            $isCorrect = false;
                            break;
                        }
                    }
                }
                break;
        }

        $answer->is_correct = $isCorrect;
        $answer->score = $isCorrect ? $question->points : 0;
        $answer->save();

        return $answer;
    }

    public function recalculateAttemptScore(QuizAttempt $attempt): QuizAttempt
    {
        $attempt->load('answers.question');

        $score = $attempt->answers->sum('score');
        $maxScore = $attempt->answers->sum(fn ($a) => $a->question->points);
        $percentage = $maxScore > 0 ? round(($score / $maxScore) * 100, 2) : 0;

        $attempt->update([
            'score' => $score,
            'max_score' => $maxScore,
            'percentage' => $percentage,
            'status' => 'graded',
        ]);

        return $attempt->fresh();
    }
}
