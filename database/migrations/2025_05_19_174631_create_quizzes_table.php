<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lesson_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('time_limit_minutes')->nullable(); // Optional time limit
            $table->integer('pass_score')->default(70); // Percentage score needed to pass
            $table->boolean('show_answers_after_submission')->default(true);
            $table->boolean('randomize_questions')->default(false);
            $table->boolean('is_published')->default(false);
            $table->integer('order')->default(0); // If multiple quizzes per lesson
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
