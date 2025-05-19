<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the progress records for the user.
     */
    public function progress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    /**
     * Get the lesson progress records for the user.
     */
    public function lessonProgress(): HasMany
    {
        return $this->hasMany(UserProgress::class)->whereNotNull('lesson_id');
    }

    /**
     * Get the quiz progress records for the user.
     */
    public function quizProgress(): HasMany
    {
        return $this->hasMany(UserProgress::class)->whereNotNull('quiz_id');
    }

    /**
     * Get all completed lessons for the user.
     */
    public function completedLessons()
    {
        return Lesson::whereIn('id', $this->lessonProgress()
            ->where('status', 'completed')
            ->pluck('lesson_id'));
    }

    /**
     * Calculate overall learning progress percentage.
     */
    public function getOverallProgressPercentage(): int
    {
        $totalLessons = Lesson::where('is_published', true)->count();
        
        if ($totalLessons === 0) {
            return 0;
        }
        
        $completedLessons = $this->lessonProgress()
            ->where('status', 'completed')
            ->count();
        
        return round(($completedLessons / $totalLessons) * 100);
    }

    /**
     * Get the progress for a specific lesson.
     */
    public function getProgressForLesson(int $lessonId): ?UserProgress
    {
        return $this->progress()
            ->where('lesson_id', $lessonId)
            ->whereNull('quiz_id')
            ->first();
    }

    /**
     * Get the progress for a specific quiz.
     */
    public function getProgressForQuiz(int $quizId): ?UserProgress
    {
        return $this->progress()
            ->where('quiz_id', $quizId)
            ->whereNull('lesson_id')
            ->first();
    }

    /**
     * Get the overall progress for a module.
     */
    public function getModuleProgress(int $moduleId): array
    {
        $module = Module::findOrFail($moduleId);
        $lessons = $module->lessons;
        
        $totalLessons = $lessons->count();
        $completedLessons = 0;
        
        foreach ($lessons as $lesson) {
            if ($this->getProgressForLesson($lesson->id)?->status === 'completed') {
                $completedLessons++;
            }
        }
        
        return [
            'total' => $totalLessons,
            'completed' => $completedLessons,
            'percentage' => $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0,
        ];
    }
}
