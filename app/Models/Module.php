<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Module extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'slug',
        'thumbnail',
        'order',
        'level',
        'duration_minutes',
        'is_published',
    ];

    /**
     * Get the lessons for the module.
     */
    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    /**
     * Get all published lessons for the module.
     */
    public function publishedLessons(): HasMany
    {
        return $this->hasMany(Lesson::class)
            ->where('is_published', true)
            ->orderBy('order');
    }

    /**
     * Calculate the total number of lessons in this module.
     */
    public function getLessonCountAttribute(): int
    {
        return $this->lessons()->count();
    }

    /**
     * Calculate completion percentage for a specific user.
     */
    public function completionPercentage($userId): int
    {
        $totalLessons = $this->lessons()->count();
        
        if ($totalLessons === 0) {
            return 0;
        }
        
        $completedLessons = UserProgress::where('user_id', $userId)
            ->whereIn('lesson_id', $this->lessons()->pluck('id'))
            ->where('status', 'completed')
            ->count();
        
        return round(($completedLessons / $totalLessons) * 100);
    }
}
