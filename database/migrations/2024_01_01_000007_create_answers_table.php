<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('quiz_attempts')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->text('student_answer')->nullable();
            $table->decimal('score', 8, 2)->nullable();
            $table->text('ai_feedback')->nullable();
            $table->decimal('ai_probability_human', 5, 2)->nullable();
            $table->decimal('ai_probability_generated', 5, 2)->nullable();
            $table->boolean('is_correct')->nullable();
            $table->timestamps();

            $table->unique(['attempt_id', 'question_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
