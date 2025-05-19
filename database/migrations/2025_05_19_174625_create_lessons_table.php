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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->string('slug')->unique();
            $table->enum('type', ['video', 'text', 'zoom', 'mixed'])->default('video');
            $table->text('content')->nullable(); // For text-based lessons
            $table->string('video_url')->nullable(); // URL to the video source
            $table->string('zoom_link')->nullable(); // Link to Zoom meeting
            $table->integer('duration_minutes')->nullable(); // Video duration or estimated completion time
            $table->boolean('has_subtitles')->default(false);
            $table->string('subtitle_url')->nullable();
            $table->integer('order')->default(0); // To maintain lesson order in a module
            $table->boolean('is_published')->default(false);
            $table->boolean('is_free')->default(false); // If accessible without subscription
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
