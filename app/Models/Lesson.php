<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lesson extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'module_id',
        'title',
        'description',
        'slug',
        'type',
        'content',
        'video_url',
        'zoom_link',
        'duration_minutes',
        'has_subtitles',
        'subtitle_url',
        'order',
        'is_published',
        'is_free',
    ];

    /**
     * Get the module that owns the lesson.
     */
    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get the quiz associated with the lesson.
     */
    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class);
    }

    /**
     * Get the quizzes associated with the lesson.
     */
    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class)->orderBy('order');
    }

    /**
     * Get the progress records for this lesson.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Check if a user has completed this lesson.
     */
    public function isCompletedByUser($userId): bool
    {
        return $this->progress()
            ->where('user_id', $userId)
            ->where('status', 'completed')
            ->exists();
    }

    /**
     * Get the completion status for a user.
     */
    public function getStatusForUser($userId): string
    {
        $progress = $this->progress()
            ->where('user_id', $userId)
            ->first();
            
        return $progress ? $progress->status : 'not_started';
    }

    /**
     * Get the video progress in seconds for a user.
     */
    public function getVideoProgressForUser($userId): int
    {
        $progress = $this->progress()
            ->where('user_id', $userId)
            ->first();
            
        return $progress ? $progress->video_progress_seconds ?? 0 : 0;
    }
    
    /**
     * Get completion percentage for a specific user.
     */
    public function getCompletionPercentage($userId): int
    {
        $progress = $this->progress()
            ->where('user_id', $userId)
            ->first();
            
        if (!$progress) {
            return 0;
        }
        
        if ($progress->status === 'completed') {
            return 100;
        }
        
        // For video lessons, calculate percentage based on video progress
        if ($this->type === 'video' && $this->duration_minutes > 0 && $progress->video_progress_seconds > 0) {
            $totalSeconds = $this->duration_minutes * 60;
            $percentage = min(round(($progress->video_progress_seconds / $totalSeconds) * 100), 99);
            return $percentage;
        }
        
        // For other types or if information is insufficient, return a default in-progress value
        return $progress->status === 'in_progress' ? 50 : 0;
    }
}
