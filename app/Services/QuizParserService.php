<?php

namespace App\Services;

use Smalot\PdfParser\Parser as PdfParser;
use ZipArchive;

class QuizParserService
{
    public function parse(string $filepath, string $extension): array
    {
        $text = match (strtolower($extension)) {
            'pdf' => $this->parsePdf($filepath),
            'docx' => $this->parseDocx($filepath),
            default => throw new \InvalidArgumentException('Unsupported file type.'),
        };

        return $this->extractQuestions($text);
    }

    protected function parsePdf(string $filepath): string
    {
        $parser = new PdfParser();
        $pdf = $parser->parseFile($filepath);

        return $pdf->getText();
    }

    protected function parseDocx(string $filepath): string
    {
        $zip = new ZipArchive();
        if ($zip->open($filepath) !== true) {
            throw new \RuntimeException('Unable to open DOCX file.');
        }

        $xml = $zip->getFromName('word/document.xml');
        $zip->close();

        if (!$xml) {
            throw new \RuntimeException('Invalid DOCX structure.');
        }

        $xml = preg_replace('/<w:tab[^\/]*\/>/', "\t", $xml);
        $xml = preg_replace('/<w:br[^\/]*\/>/', "\n", $xml);
        $text = strip_tags($xml);

        return html_entity_decode($text, ENT_QUOTES | ENT_XML1, 'UTF-8');
    }

    protected function extractQuestions(string $text): array
    {
        $text = preg_replace("/\r\n|\r/", "\n", $text);
        $text = preg_replace("/\n{3,}/", "\n\n", trim($text));

        $blocks = preg_split('/(?=\n?\d+[\.\)]\s)/', $text);
        $questions = [];
        $order = 1;

        foreach ($blocks as $block) {
            $block = trim($block);
            if (strlen($block) < 5) {
                continue;
            }

            $parsed = $this->parseQuestionBlock($block);
            if ($parsed) {
                $parsed['order_number'] = $order++;
                $questions[] = $parsed;
            }
        }

        return $questions;
    }

    protected function parseQuestionBlock(string $block): ?array
    {
        $block = preg_replace('/^\d+[\.\)]\s*/', '', $block);

        if (preg_match('/^(True|False)\s*[\.\)]?\s*(.+)$/is', $block, $tfMatch)) {
            return [
                'question_text' => trim($tfMatch[2]),
                'question_type' => 'true_false',
                'correct_answer' => ucfirst(strtolower(trim($tfMatch[1]))),
                'points' => 1,
            ];
        }

        if (preg_match_all('/^[A-Da-d][\.\)]\s*(.+)$/m', $block, $choices, PREG_SET_ORDER)) {
            $questionText = trim(preg_replace('/^[A-Da-d][\.\)]\s*.+$/m', '', $block));

            return [
                'question_text' => $questionText,
                'question_type' => 'multiple_choice',
                'choice_a' => $choices[0][1] ?? null,
                'choice_b' => $choices[1][1] ?? null,
                'choice_c' => $choices[2][1] ?? null,
                'choice_d' => $choices[3][1] ?? null,
                'correct_answer' => 'A',
                'points' => 1,
            ];
        }

        if (preg_match('/^(Essay|Explain|Discuss|Describe)[:\s]/i', $block) || str_word_count($block) > 40) {
            return [
                'question_text' => $block,
                'question_type' => 'essay',
                'rubric' => 'Evaluate based on relevance, accuracy, completeness, grammar, and critical thinking.',
                'points' => 5,
            ];
        }

        return [
            'question_text' => $block,
            'question_type' => 'identification',
            'correct_answer' => '',
            'points' => 1,
        ];
    }
}
