<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QuizQuestion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'quiz_id',
        'question_text',
        'question_type',
        'explanation',
        'points',
        'audio_url',
        'image_url',
        'order',
    ];

    /**
     * Get the quiz that owns the question.
     */
    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     * Get the answers for the question.
     */
    public function answers(): HasMany
    {
        return $this->hasMany(QuizAnswer::class);
    }
    
    /**
     * Get only the correct answers for the question.
     */
    public function correctAnswers()
    {
        return $this->answers()->where('is_correct', true)->get();
    }
    
    /**
     * Check if the provided answer is correct.
     */
    public function isCorrect($userAnswer): bool
    {
        if ($this->question_type === 'multiple_choice') {
            // For multiple choice, we need to check if the selected answer is marked as correct
            $correctAnswerIds = $this->answers()->where('is_correct', true)->pluck('id')->toArray();
            return in_array($userAnswer, $correctAnswerIds);
        } 
        elseif ($this->question_type === 'true_false') {
            // For true/false, simply compare with the correct answer
            $correctAnswer = $this->answers()->where('is_correct', true)->first();
            return $correctAnswer && $correctAnswer->answer_text === $userAnswer;
        }
        elseif ($this->question_type === 'short_answer') {
            // For short answers, we'll do a case-insensitive comparison with all correct answers
            $correctAnswers = $this->answers()->where('is_correct', true)->pluck('answer_text')->toArray();
            
            foreach ($correctAnswers as $answer) {
                // Simple exact match
                if (strtolower(trim($userAnswer)) === strtolower(trim($answer))) {
                    return true;
                }
            }
            
            return false;
        }
        elseif ($this->question_type === 'fill_in_blank') {
            // For fill in the blanks with multiple answers
            if (!is_array($userAnswer)) {
                return false;
            }
            
            $correctAnswers = $this->answers()->where('is_correct', true)->pluck('answer_text')->toArray();
            
            // Check that we have the same number of answers
            if (count($userAnswer) !== count($correctAnswers)) {
                return false;
            }
            
            // Check each blank
            foreach ($userAnswer as $index => $answer) {
                if (!isset($correctAnswers[$index]) || strtolower(trim($answer)) !== strtolower(trim($correctAnswers[$index]))) {
                    return false;
                }
            }
            
            return true;
        }
        
        return false;
    }
}
