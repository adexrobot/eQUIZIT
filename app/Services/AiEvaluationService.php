<?php

namespace App\Services;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiEvaluationService
{
    public function evaluateEssay(Answer $answer, Question $question): Answer
    {
        $apiKey = config('services.openai.api_key');
        $studentAnswer = trim((string) $answer->student_answer);

        if (empty($studentAnswer)) {
            $answer->update([
                'score' => 0,
                'ai_feedback' => json_encode([
                    'strengths' => [],
                    'improvements' => ['No answer was provided.'],
                    'recommended_learning' => ['Review the topic and attempt the question again.'],
                    'summary' => 'No answer submitted.',
                ]),
                'ai_probability_human' => 50,
                'ai_probability_generated' => 50,
            ]);

            return $answer->fresh();
        }

        if (empty($apiKey)) {
            return $this->fallbackEvaluation($answer, $question);
        }

        try {
            $prompt = $this->buildPrompt($question, $studentAnswer);

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(60)->post(config('services.openai.base_url') . '/chat/completions', [
                'model' => config('services.openai.model', 'gpt-4o-mini'),
                'messages' => [
                    ['role' => 'system', 'content' => 'You are an academic essay evaluator. Respond ONLY with valid JSON.'],
                    ['role' => 'user', 'content' => $prompt],
                ],
                'temperature' => 0.3,
            ]);

            if ($response->successful()) {
                $content = $response->json('choices.0.message.content', '');
                $parsed = $this->parseAiResponse($content, (float) $question->points);

                $answer->update([
                    'score' => $parsed['score'],
                    'ai_feedback' => json_encode($parsed['feedback']),
                    'ai_probability_human' => $parsed['human_probability'],
                    'ai_probability_generated' => $parsed['ai_probability'],
                ]);

                return $answer->fresh();
            }

            Log::warning('OpenAI API error', ['body' => $response->body()]);
        } catch (\Throwable $e) {
            Log::error('AI evaluation failed', ['message' => $e->getMessage()]);
        }

        return $this->fallbackEvaluation($answer, $question);
    }

    protected function buildPrompt(Question $question, string $studentAnswer): string
    {
        $maxPoints = $question->points;
        $rubric = $question->rubric ?: 'Evaluate based on relevance, accuracy, completeness, grammar, and critical thinking.';

        return <<<PROMPT
Evaluate this student essay answer.

Question: {$question->question_text}
Rubric: {$rubric}
Maximum Points: {$maxPoints}
Student Answer: {$studentAnswer}

Return JSON with this exact structure:
{
  "score": <number 0 to {$maxPoints}>,
  "feedback": {
    "strengths": ["..."],
    "improvements": ["..."],
    "recommended_learning": ["..."],
    "summary": "..."
  },
  "writing_analysis": {
    "human_probability": <0-100>,
    "ai_probability": <0-100>,
    "note": "Indicator only - not definitive proof of AI use"
  }
}
PROMPT;
    }

    protected function parseAiResponse(string $content, float $maxPoints): array
    {
        $content = trim($content);
        if (preg_match('/\{.*\}/s', $content, $matches)) {
            $content = $matches[0];
        }

        $data = json_decode($content, true) ?: [];

        $score = min($maxPoints, max(0, (float) ($data['score'] ?? 0)));
        $human = min(100, max(0, (float) ($data['writing_analysis']['human_probability'] ?? 75)));
        $ai = min(100, max(0, (float) ($data['writing_analysis']['ai_probability'] ?? 25)));

        return [
            'score' => $score,
            'feedback' => $data['feedback'] ?? [
                'strengths' => ['Answer submitted successfully.'],
                'improvements' => ['Expand your explanation with more detail.'],
                'recommended_learning' => ['Review course materials on this topic.'],
                'summary' => 'Evaluation completed.',
            ],
            'human_probability' => $human,
            'ai_probability' => $ai,
        ];
    }

    protected function fallbackEvaluation(Answer $answer, Question $question): Answer
    {
        $wordCount = str_word_count((string) $answer->student_answer);
        $maxPoints = (float) $question->points;
        $ratio = min(1, $wordCount / 50);
        $score = round($maxPoints * $ratio * 0.7, 2);

        $answer->update([
            'score' => $score,
            'ai_feedback' => json_encode([
                'strengths' => $wordCount >= 30 ? ['Provided a substantive response.'] : [],
                'improvements' => $wordCount < 30 ? ['Provide a more detailed explanation.'] : ['Add specific examples to support your points.'],
                'recommended_learning' => ['Review the rubric and course materials.'],
                'summary' => 'Automated evaluation (OpenAI API not configured). Configure OPENAI_API_KEY for full AI grading.',
            ]),
            'ai_probability_human' => 70,
            'ai_probability_generated' => 30,
        ]);

        return $answer->fresh();
    }
}
