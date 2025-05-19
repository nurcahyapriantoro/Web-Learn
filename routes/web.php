<?php

use App\Livewire\LessonComponent;
use App\Models\Lesson;
use App\Models\Module;
use App\Models\Quiz;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

// Learning Routes
Route::prefix('learn')->group(function () {
    // Modules
    Route::get('/modules', function () {
        $modules = Module::where('is_published', true)
            ->orderBy('order')
            ->get();
        return view('modules.index', compact('modules'));
    })->name('learn.modules');

    Route::get('/module/{slug}', function (string $slug) {
        $module = Module::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
        
        $lessons = $module->publishedLessons;
        
        return view('modules.show', compact('module', 'lessons'));
    })->name('module.show');

    // Lessons
    Route::get('/lesson/{slug}', function (string $slug) {
        $lesson = Lesson::where('slug', $slug)
            ->where('is_published', true)
            ->firstOrFail();
        
        return view('lessons.show', [
            'lesson' => $lesson
        ]);
    })->name('lesson.show');
    
    // Standalone Quiz
    Route::get('/quiz/{id}', function (int $id) {
        $quiz = Quiz::where('id', $id)
            ->where('is_published', true)
            ->firstOrFail();
        
        return view('quizzes.show', compact('quiz'));
    })->name('quiz.show');
});

require __DIR__.'/auth.php';
