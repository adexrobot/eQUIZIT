<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('subject');
            $table->string('course_code');
            $table->text('description')->nullable();
            $table->string('quiz_code')->unique();
            $table->integer('duration')->default(60);
            $table->integer('max_attempts')->default(1);
            $table->timestamp('available_from')->nullable();
            $table->timestamp('available_until')->nullable();
            $table->boolean('randomize_questions')->default(true);
            $table->enum('status', ['draft', 'published', 'closed'])->default('draft');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
