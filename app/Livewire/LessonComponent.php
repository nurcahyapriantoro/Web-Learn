<?php

namespace App\Livewire;

use App\Models\Lesson;
use App\Models\Module;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LessonComponent extends Component
{
    public Lesson $lesson;
    public Module $module;
    public bool $showVideoPlayer = false;
    public bool $showQuiz = false;
    public array $nextPrevLessons = [
        'previous' => null,
        'next' => null,
    ];
    public bool $isCompleted = false;
    
    public function mount(Lesson $lesson)
    {
        $this->lesson = $lesson;
        $this->module = $lesson->module;
        
        // Determine if this lesson has a video
        $this->showVideoPlayer = !empty($lesson->video_url) && in_array($lesson->type, ['video', 'mixed']);
        
        // Determine if this lesson has a quiz
        $this->showQuiz = $lesson->quiz()->exists();
        
        // Get next and previous lessons
        $this->loadNextPrevLessons();
        
        // Check if the lesson is completed
        if (Auth::check()) {
            $this->isCompleted = $lesson->isCompletedByUser(Auth::id());
            
            // Mark as accessed if not already tracking
            $this->trackAccess();
        }
    }
    
    protected function loadNextPrevLessons()
    {
        $moduleId = $this->lesson->module_id;
        $currentOrder = $this->lesson->order;
        
        // Find previous lesson
        $previous = Lesson::where('module_id', $moduleId)
            ->where('order', '<', $currentOrder)
            ->where('is_published', true)
            ->orderBy('order', 'desc')
            ->first();
        
        // Find next lesson
        $next = Lesson::where('module_id', $moduleId)
            ->where('order', '>', $currentOrder)
            ->where('is_published', true)
            ->orderBy('order', 'asc')
            ->first();
        
        $this->nextPrevLessons['previous'] = $previous?->id ? [
            'id' => $previous->id,
            'title' => $previous->title,
            'slug' => $previous->slug,
        ] : null;
        
        $this->nextPrevLessons['next'] = $next?->id ? [
            'id' => $next->id,
            'title' => $next->title,
            'slug' => $next->slug,
        ] : null;
    }
    
    protected function trackAccess()
    {
        if (!Auth::check()) {
            return;
        }
        
        $progress = UserProgress::firstOrNew([
            'user_id' => Auth::id(),
            'lesson_id' => $this->lesson->id,
            'quiz_id' => null,
        ]);
        
        if ($progress->status === 'not_started') {
            $progress->status = 'in_progress';
        }
        
        $progress->last_accessed_at = now();
        $progress->save();
    }
    
    public function markAsCompleted()
    {
        if (!Auth::check()) {
            $this->dispatch('showLoginPrompt');
            return;
        }
        
        $progress = UserProgress::firstOrNew([
            'user_id' => Auth::id(),
            'lesson_id' => $this->lesson->id,
            'quiz_id' => null,
        ]);
        
        $progress->status = 'completed';
        $progress->completed_at = now();
        $progress->last_accessed_at = now();
        $progress->save();
        
        $this->isCompleted = true;
        
        $this->dispatch('lessonCompleted', [
            'lessonId' => $this->lesson->id,
            'moduleId' => $this->module->id,
        ]);
    }
    
    public function render()
    {
        return view('livewire.lesson-component');
    }
}
