<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->text('question_text');
            $table->enum('question_type', ['multiple_choice', 'true_false', 'identification', 'essay']);
            $table->string('choice_a')->nullable();
            $table->string('choice_b')->nullable();
            $table->string('choice_c')->nullable();
            $table->string('choice_d')->nullable();
            $table->string('correct_answer')->nullable();
            $table->decimal('points', 8, 2)->default(1);
            $table->text('rubric')->nullable();
            $table->integer('order_number')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
