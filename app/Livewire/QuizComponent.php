<?php

namespace App\Livewire;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class QuizComponent extends Component
{
    public Quiz $quiz;
    public array $questions = [];
    public array $userAnswers = [];
    public array $correctAnswers = [];
    public int $currentQuestionIndex = 0;
    public bool $quizCompleted = false;
    public int $score = 0;
    public int $totalPossibleScore = 0;
    public bool $showAnswers = false;
    public bool $timerActive = false;
    public int $remainingTime = 0;
    public array $explanations = [];
    
    public function mount(Quiz $quiz)
    {
        $this->quiz = $quiz;
        
        // Check if user has already completed this quiz
        if (Auth::check()) {
            $progress = UserProgress::where('user_id', Auth::id())
                ->where('quiz_id', $quiz->id)
                ->whereNull('lesson_id')
                ->first();
                
            if ($progress && $progress->status === 'completed') {
                $this->quizCompleted = true;
                $this->score = $progress->quiz_score;
                $this->totalPossibleScore = $quiz->total_points;
                $this->showAnswers = true;
            }
        }
        
        // Load questions
        if ($quiz->randomize_questions) {
            $this->questions = $quiz->questions()->inRandomOrder()->get()->toArray();
        } else {
            $this->questions = $quiz->questions()->orderBy('order')->get()->toArray();
        }
        
        // Initialize answers array
        foreach ($this->questions as $index => $question) {
            $this->userAnswers[$index] = null;
            
            // Store correct answers for later comparison
            $question = QuizQuestion::find($question['id']);
            $this->correctAnswers[$index] = $question->correctAnswers()->pluck('id')->toArray();
            
            // Store explanations
            $this->explanations[$index] = $question['explanation'];
        }
        
        // Set up timer if quiz has time limit
        if ($quiz->time_limit_minutes) {
            $this->remainingTime = $quiz->time_limit_minutes * 60;
            $this->timerActive = true;
        }
    }
    
    public function nextQuestion()
    {
        // Don't proceed if current question is unanswered
        if ($this->userAnswers[$this->currentQuestionIndex] === null) {
            $this->dispatch('showAlert', [
                'type' => 'warning',
                'message' => 'Please answer the question before proceeding.',
            ]);
            return;
        }
        
        if ($this->currentQuestionIndex < count($this->questions) - 1) {
            $this->currentQuestionIndex++;
        }
    }
    
    public function previousQuestion()
    {
        if ($this->currentQuestionIndex > 0) {
            $this->currentQuestionIndex--;
        }
    }
    
    public function selectAnswer($questionIndex, $answerId)
    {
        // For multiple choice questions, just store the selected answer ID
        $this->userAnswers[$questionIndex] = $answerId;
    }
    
    public function submitTextAnswer($questionIndex, $answer)
    {
        // For text-based questions, store the text answer
        $this->userAnswers[$questionIndex] = $answer;
    }
    
    public function submitQuiz()
    {
        // Ensure all questions are answered
        foreach ($this->userAnswers as $index => $answer) {
            if ($answer === null) {
                $this->dispatch('showAlert', [
                    'type' => 'warning',
                    'message' => 'Please answer all questions before submitting.',
                ]);
                return;
            }
        }
        
        // Calculate score
        $score = 0;
        foreach ($this->questions as $index => $questionData) {
            $question = QuizQuestion::find($questionData['id']);
            $userAnswer = $this->userAnswers[$index];
            
            if ($question->isCorrect($userAnswer)) {
                $score += $question->points;
            }
        }
        
        $this->score = $score;
        $this->totalPossibleScore = $this->quiz->total_points;
        $this->quizCompleted = true;
        $this->showAnswers = $this->quiz->show_answers_after_submission;
        
        // Save progress if user is authenticated
        if (Auth::check()) {
            $progress = UserProgress::firstOrNew([
                'user_id' => Auth::id(),
                'quiz_id' => $this->quiz->id,
                'lesson_id' => null,
            ]);
            
            $progress->quiz_score = $score;
            $progress->status = 'completed';
            $progress->completed_at = now();
            $progress->last_accessed_at = now();
            $progress->save();
            
            // If this quiz is the only quiz for a lesson, also mark the lesson as in progress
            $lesson = $this->quiz->lesson;
            if ($lesson) {
                $lessonProgress = UserProgress::firstOrNew([
                    'user_id' => Auth::id(),
                    'lesson_id' => $lesson->id,
                    'quiz_id' => null,
                ]);
                
                if ($lessonProgress->status !== 'completed') {
                    $lessonProgress->status = 'in_progress';
                    $lessonProgress->last_accessed_at = now();
                    $lessonProgress->save();
                }
            }
        }
        
        $this->dispatch('quizCompleted', [
            'score' => $score,
            'totalPossible' => $this->totalPossibleScore,
            'percentage' => round(($score / $this->totalPossibleScore) * 100),
            'passed' => ($score / $this->totalPossibleScore) * 100 >= $this->quiz->pass_score,
        ]);
    }
    
    public function goToQuestion($index)
    {
        if ($index >= 0 && $index < count($this->questions)) {
            $this->currentQuestionIndex = $index;
        }
    }
    
    public function resetQuiz()
    {
        // Reset the quiz if user wants to retry
        $this->userAnswers = array_fill(0, count($this->questions), null);
        $this->currentQuestionIndex = 0;
        $this->quizCompleted = false;
        $this->score = 0;
        $this->showAnswers = false;
        
        // Reset timer if applicable
        if ($this->quiz->time_limit_minutes) {
            $this->remainingTime = $this->quiz->time_limit_minutes * 60;
            $this->timerActive = true;
        }
    }
    
    public function updateTimer($secondsRemaining)
    {
        $this->remainingTime = $secondsRemaining;
        
        if ($secondsRemaining <= 0 && $this->timerActive) {
            $this->timerActive = false;
            $this->submitQuiz();
        }
    }
    
    public function isAnswerCorrect($questionIndex, $answerId = null)
    {
        if (!$this->quizCompleted || !$this->showAnswers) {
            return false;
        }
        
        $answerId = $answerId ?? $this->userAnswers[$questionIndex];
        $question = QuizQuestion::find($this->questions[$questionIndex]['id']);
        
        return $question->isCorrect($answerId);
    }
    
    public function render()
    {
        return view('livewire.quiz-component');
    }
}
