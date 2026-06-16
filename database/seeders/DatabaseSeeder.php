<?php

namespace Database\Seeders;

use App\Models\FacultyProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'System Administrator',
            'email' => 'admin@equizmona.edu',
            'password' => Hash::make('admin12345'),
            'role' => 'admin',
        ]);

        $faculty = User::create([
            'name' => 'Dr. Maria Santos',
            'email' => 'faculty@equizmona.edu',
            'password' => Hash::make('faculty12345'),
            'role' => 'faculty',
        ]);

        FacultyProfile::create([
            'user_id' => $faculty->id,
            'faculty_id' => 'FAC-2026-001',
            'department' => 'College of Computer Studies',
            'program' => 'BS Information Technology',
            'contact_number' => '09171234567',
        ]);

        $quiz = $faculty->quizzes()->create([
            'title' => 'Introduction to Programming - Quiz 1',
            'subject' => 'Computer Programming 1',
            'course_code' => 'CS101',
            'description' => 'Sample quiz covering basic programming concepts.',
            'quiz_code' => 'QUIZ2026CS101',
            'duration' => 30,
            'max_attempts' => 2,
            'status' => 'published',
            'randomize_questions' => true,
        ]);

        $quiz->questions()->createMany([
            [
                'question_text' => 'What is CPU?',
                'question_type' => 'multiple_choice',
                'choice_a' => 'Central Processing Unit',
                'choice_b' => 'Computer Processing Unit',
                'choice_c' => 'Control Program Unit',
                'choice_d' => 'None of the above',
                'correct_answer' => 'A',
                'points' => 2,
                'order_number' => 1,
            ],
            [
                'question_text' => 'PHP is a server-side scripting language.',
                'question_type' => 'true_false',
                'correct_answer' => 'True',
                'points' => 1,
                'order_number' => 2,
            ],
            [
                'question_text' => 'What does HTML stand for?',
                'question_type' => 'identification',
                'correct_answer' => 'HyperText Markup Language|hypertext markup language',
                'points' => 2,
                'order_number' => 3,
            ],
            [
                'question_text' => 'Explain the difference between variables and constants in programming.',
                'question_type' => 'essay',
                'rubric' => 'Evaluate based on relevance, accuracy, completeness, grammar, and critical thinking.',
                'points' => 5,
                'order_number' => 4,
            ],
        ]);
    }
}
