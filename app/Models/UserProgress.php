<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserProgress extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'lesson_id',
        'quiz_id',
        'status',
        'quiz_score',
        'video_progress_seconds',
        'completed_at',
        'last_accessed_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'completed_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'video_progress_seconds' => 'integer',
    ];

    /**
     * Ensure video_progress_seconds is always an integer
     */
    public function getVideoProgressSecondsAttribute($value)
    {
        return $value ?? 0;
    }

    /**
     * Get the user that owns the progress.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the lesson associated with the progress.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the quiz associated with the progress.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Calculate the completion percentage for a video lesson.
     */
    public function getVideoPercentageAttribute(): int
    {
        if (!$this->video_progress_seconds || !$this->lesson || !$this->lesson->duration_minutes) {
            return 0;
        }
        
        $totalSeconds = $this->lesson->duration_minutes * 60;
        $percentage = ($this->video_progress_seconds / $totalSeconds) * 100;
        
        return min(100, round($percentage));
    }

    /**
     * Calculate the quiz score percentage.
     */
    public function getQuizPercentageAttribute(): int
    {
        if (!$this->quiz_score || !$this->quiz || !$this->quiz->total_points) {
            return 0;
        }
        
        $percentage = ($this->quiz_score / $this->quiz->total_points) * 100;
        
        return min(100, round($percentage));
    }

    /**
     * Mark a lesson as completed.
     */
    public function markAsCompleted(): void
    {
        $this->status = 'completed';
        $this->completed_at = now();
        $this->save();
    }

    /**
     * Update video progress.
     */
    public function updateVideoProgress(int $seconds): void
    {
        $this->video_progress_seconds = $seconds;
        $this->last_accessed_at = now();
        
        // If video is 95% complete, mark as completed
        if ($this->lesson && $this->lesson->duration_minutes) {
            $totalSeconds = $this->lesson->duration_minutes * 60;
            if ($seconds >= $totalSeconds * 0.95) {
                $this->status = 'completed';
                $this->completed_at = $this->completed_at ?? now();
            } else {
                $this->status = 'in_progress';
            }
        }
        
        $this->save();
    }
}
