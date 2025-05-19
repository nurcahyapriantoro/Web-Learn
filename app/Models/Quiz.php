<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'lesson_id',
        'title',
        'description',
        'time_limit_minutes',
        'pass_score',
        'show_answers_after_submission',
        'randomize_questions',
        'is_published',
        'order',
    ];

    /**
     * Get the lesson that owns the quiz.
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get the questions for the quiz.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)
            ->orderBy('order');
    }

    /**
     * Get the progress records for this quiz.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Calculate the total points available in this quiz.
     */
    public function getTotalPointsAttribute(): int
    {
        return $this->questions->sum('points');
    }

    /**
     * Check if a user has passed this quiz.
     */
    public function isPassedByUser($userId): bool
    {
        $progress = $this->progress()
            ->where('user_id', $userId)
            ->first();
            
        if (!$progress || !$progress->quiz_score) {
            return false;
        }
        
        $totalPoints = $this->total_points;
        if ($totalPoints === 0) {
            return false;
        }
        
        $percentageScore = ($progress->quiz_score / $totalPoints) * 100;
        return $percentageScore >= $this->pass_score;
    }

    /**
     * Get the score for a user.
     */
    public function getScoreForUser($userId): ?int
    {
        $progress = $this->progress()
            ->where('user_id', $userId)
            ->first();
            
        return $progress ? $progress->quiz_score : null;
    }
}
