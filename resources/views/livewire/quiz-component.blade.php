<div class="quiz-container bg-white rounded-xl shadow-md overflow-hidden p-6 max-w-4xl mx-auto">
    @if(!$quizCompleted)
        <div class="quiz-header mb-8">
            <h2 class="text-2xl font-bold text-primary-800 mb-2">{{ $quiz->title }}</h2>
            <p class="text-secondary-600 mb-4">{{ $quiz->description }}</p>
            
            @if($timerActive)
                <div class="timer-container mb-4 flex items-center" x-data="quizTimer({{ $remainingTime }})" x-init="startTimer()">
                    <div class="bg-secondary-100 text-secondary-800 px-4 py-2 rounded-lg flex items-center">
                        <svg class="h-5 w-5 mr-2 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                        <span x-text="formatTime(timeRemaining)"></span>
                    </div>
                </div>
            @endif
            
            <!-- Question Navigation -->
            <div class="question-navigation flex flex-wrap gap-2 mb-4">
                @foreach($questions as $index => $question)
                    <button 
                        wire:click="goToQuestion({{ $index }})" 
                        class="w-8 h-8 rounded-full flex items-center justify-center text-sm font-medium transition-all duration-300
                        {{ $currentQuestionIndex === $index ? 'bg-primary-600 text-white' : 
                           ($userAnswers[$index] !== null ? 'bg-primary-100 text-primary-700 border border-primary-300' : 
                           'bg-gray-100 text-secondary-700 hover:bg-gray-200') }}">
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>
        </div>
        
        @if(isset($questions[$currentQuestionIndex]))
            <div class="question-container bg-gray-50 rounded-lg p-6 mb-6 animate-fade-in">
                <div class="question-header mb-4">
                    <h3 class="text-xl font-semibold text-secondary-900 mb-2">Question {{ $currentQuestionIndex + 1 }} of {{ count($questions) }}</h3>
                    
                    @if(isset($questions[$currentQuestionIndex]['points']) && $questions[$currentQuestionIndex]['points'] > 1)
                        <div class="text-sm text-primary-600 font-medium">{{ $questions[$currentQuestionIndex]['points'] }} points</div>
                    @endif
                </div>
                
                <div class="question-text text-lg text-secondary-800 mb-6">
                    {{ $questions[$currentQuestionIndex]['question_text'] }}
                    
                    @if(isset($questions[$currentQuestionIndex]['image_url']) && $questions[$currentQuestionIndex]['image_url'])
                        <div class="mt-4">
                            <img src="{{ $questions[$currentQuestionIndex]['image_url'] }}" alt="Question image" class="max-w-full rounded-lg">
                        </div>
                    @endif
                    
                    @if(isset($questions[$currentQuestionIndex]['audio_url']) && $questions[$currentQuestionIndex]['audio_url'])
                        <div class="mt-4">
                            <audio controls class="w-full">
                                <source src="{{ $questions[$currentQuestionIndex]['audio_url'] }}" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                        </div>
                    @endif
                </div>
                
                <div class="answers-container">
                    @if($questions[$currentQuestionIndex]['question_type'] === 'multiple_choice')
                        <div class="space-y-3">
                            @php
                                $questionId = $questions[$currentQuestionIndex]['id'];
                                $question = App\Models\QuizQuestion::find($questionId);
                                $answers = $question->answers()->orderBy('order')->get();
                            @endphp
                            
                            @foreach($answers as $answer)
                                <div 
                                    wire:click="selectAnswer({{ $currentQuestionIndex }}, {{ $answer->id }})"
                                    class="quiz-option cursor-pointer p-4 rounded-lg transition-all duration-300
                                    {{ isset($userAnswers[$currentQuestionIndex]) && $userAnswers[$currentQuestionIndex] == $answer->id 
                                        ? 'bg-primary-50 border-primary-300 border-2' 
                                        : 'border border-gray-200 hover:border-primary-300 hover:bg-primary-50' }}">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 h-5 w-5 rounded-full border-2 
                                            {{ isset($userAnswers[$currentQuestionIndex]) && $userAnswers[$currentQuestionIndex] == $answer->id 
                                                ? 'border-primary-600 bg-primary-600' 
                                                : 'border-gray-300' }} 
                                            mr-3 mt-0.5">
                                            @if(isset($userAnswers[$currentQuestionIndex]) && $userAnswers[$currentQuestionIndex] == $answer->id)
                                                <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 16 16">
                                                    <circle cx="8" cy="8" r="4" />
                                                </svg>
                                            @endif
                                        </div>
                                        <span class="text-secondary-800">{{ $answer->answer_text }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @elseif($questions[$currentQuestionIndex]['question_type'] === 'true_false')
                        <div class="space-y-3">
                            <div 
                                wire:click="selectAnswer({{ $currentQuestionIndex }}, 'true')"
                                class="quiz-option cursor-pointer p-4 rounded-lg transition-all duration-300
                                {{ isset($userAnswers[$currentQuestionIndex]) && $userAnswers[$currentQuestionIndex] === 'true' 
                                    ? 'bg-primary-50 border-primary-300 border-2' 
                                    : 'border border-gray-200 hover:border-primary-300 hover:bg-primary-50' }}">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-5 w-5 rounded-full border-2 
                                        {{ isset($userAnswers[$currentQuestionIndex]) && $userAnswers[$currentQuestionIndex] === 'true' 
                                            ? 'border-primary-600 bg-primary-600' 
                                            : 'border-gray-300' }} 
                                        mr-3">
                                        @if(isset($userAnswers[$currentQuestionIndex]) && $userAnswers[$currentQuestionIndex] === 'true')
                                            <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 16 16">
                                                <circle cx="8" cy="8" r="4" />
                                            </svg>
                                        @endif
                                    </div>
                                    <span class="text-secondary-800">True</span>
                                </div>
                            </div>
                            
                            <div 
                                wire:click="selectAnswer({{ $currentQuestionIndex }}, 'false')"
                                class="quiz-option cursor-pointer p-4 rounded-lg transition-all duration-300
                                {{ isset($userAnswers[$currentQuestionIndex]) && $userAnswers[$currentQuestionIndex] === 'false' 
                                    ? 'bg-primary-50 border-primary-300 border-2' 
                                    : 'border border-gray-200 hover:border-primary-300 hover:bg-primary-50' }}">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-5 w-5 rounded-full border-2 
                                        {{ isset($userAnswers[$currentQuestionIndex]) && $userAnswers[$currentQuestionIndex] === 'false' 
                                            ? 'border-primary-600 bg-primary-600' 
                                            : 'border-gray-300' }} 
                                        mr-3">
                                        @if(isset($userAnswers[$currentQuestionIndex]) && $userAnswers[$currentQuestionIndex] === 'false')
                                            <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 16 16">
                                                <circle cx="8" cy="8" r="4" />
                                            </svg>
                                        @endif
                                    </div>
                                    <span class="text-secondary-800">False</span>
                                </div>
                            </div>
                        </div>
                    @elseif($questions[$currentQuestionIndex]['question_type'] === 'short_answer')
                        <div class="space-y-3">
                            <input 
                                type="text" 
                                wire:model.defer="userAnswers.{{ $currentQuestionIndex }}"
                                class="form-input w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                placeholder="Enter your answer"
                            >
                            <button 
                                wire:click="submitTextAnswer({{ $currentQuestionIndex }}, $event.target.previousElementSibling.value)" 
                                class="btn btn-primary">
                                Submit Answer
                            </button>
                        </div>
                    @elseif($questions[$currentQuestionIndex]['question_type'] === 'fill_in_blank')
                        <div class="space-y-3">
                            @php
                                $blanks = preg_match_all('/___+/', $questions[$currentQuestionIndex]['question_text'], $matches);
                            @endphp
                            
                            @for($i = 0; $i < $blanks; $i++)
                                <div class="mb-2">
                                    <label class="block text-sm font-medium text-secondary-700 mb-1">Blank {{ $i + 1 }}</label>
                                    <input 
                                        type="text" 
                                        wire:model.defer="userAnswers.{{ $currentQuestionIndex }}.{{ $i }}"
                                        class="form-input w-full rounded-lg border-gray-300 focus:border-primary-500 focus:ring focus:ring-primary-200 focus:ring-opacity-50"
                                        placeholder="Fill in the blank"
                                    >
                                </div>
                            @endfor
                            
                            <button 
                                wire:click="nextQuestion" 
                                class="btn btn-primary">
                                Confirm Answer
                            </button>
                        </div>
                    @endif
                </div>
            </div>
            
            <div class="navigation-buttons flex justify-between mt-8">
                <button 
                    wire:click="previousQuestion" 
                    class="btn btn-secondary {{ $currentQuestionIndex === 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                    {{ $currentQuestionIndex === 0 ? 'disabled' : '' }}>
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Previous
                </button>
                
                @if($currentQuestionIndex < count($questions) - 1)
                    <button 
                        wire:click="nextQuestion" 
                        class="btn btn-primary">
                        Next
                        <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                @else
                    <button 
                        wire:click="submitQuiz" 
                        class="btn btn-primary">
                        Submit Quiz
                    </button>
                @endif
            </div>
        @endif
    @else
        <!-- Quiz Results Section -->
        <div class="quiz-results animate-fade-in">
            <h2 class="text-2xl font-bold text-primary-800 mb-4">Quiz Results</h2>
            
            <div class="results-summary bg-secondary-50 rounded-xl p-6 mb-8 border border-secondary-100">
                <div class="flex flex-col md:flex-row items-center justify-between">
                    <div class="text-center md:text-left mb-4 md:mb-0">
                        <p class="text-secondary-600 mb-1">Your score</p>
                        <div class="flex items-center">
                            <span class="text-3xl font-bold text-primary-700">{{ $score }}</span>
                            <span class="text-secondary-600 mx-2">/</span>
                            <span class="text-xl text-secondary-700">{{ $totalPossibleScore }}</span>
                        </div>
                        <p class="text-secondary-600 mt-1">
                            {{ round(($score / $totalPossibleScore) * 100) }}% 
                            @if(($score / $totalPossibleScore) * 100 >= $quiz->pass_score)
                                <span class="text-green-600 font-medium">(Passed)</span>
                            @else
                                <span class="text-red-600 font-medium">(Failed)</span>
                            @endif
                        </p>
                    </div>
                    
                    <div class="relative w-24 h-24">
                        <svg class="w-full h-full" viewBox="0 0 36 36">
                            <path
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                fill="none"
                                stroke="#E2E8F0"
                                stroke-width="3"
                                stroke-dasharray="100, 100"
                            />
                            <path
                                d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                fill="none"
                                stroke="#0A84FF"
                                stroke-width="3"
                                stroke-dasharray="{{ round(($score / $totalPossibleScore) * 100) }}, 100"
                            />
                            <text x="18" y="20.5" class="text-3xl font-bold" text-anchor="middle" fill="#334155">{{ round(($score / $totalPossibleScore) * 100) }}%</text>
                        </svg>
                    </div>
                </div>
            </div>
            
            @if($showAnswers)
                <div class="question-review space-y-8 mb-8">
                    <h3 class="text-xl font-semibold text-secondary-800 mb-4">Review Questions</h3>
                    
                    @foreach($questions as $index => $question)
                        <div class="question-item bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow duration-300">
                            <h4 class="text-lg font-medium text-secondary-900 mb-3">Question {{ $index + 1 }}: {{ $question['question_text'] }}</h4>
                            
                            @php
                                $questionModel = App\Models\QuizQuestion::find($question['id']);
                                $isCorrect = $this->isAnswerCorrect($index);
                                $userAnswer = $userAnswers[$index] ?? null;
                            @endphp
                            
                            <div class="user-answer mb-4">
                                <p class="text-sm font-medium text-secondary-700 mb-1">Your answer:</p>
                                
                                @if($question['question_type'] === 'multiple_choice')
                                    @php
                                        $answer = App\Models\QuizAnswer::find($userAnswer);
                                    @endphp
                                    <div class="p-3 rounded-lg {{ $isCorrect ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                                        <div class="flex items-center">
                                            @if($isCorrect)
                                                <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                            <span class="{{ $isCorrect ? 'text-green-800' : 'text-red-800' }}">
                                                {{ $answer ? $answer->answer_text : 'No answer selected' }}
                                            </span>
                                        </div>
                                    </div>
                                @elseif($question['question_type'] === 'short_answer' || $question['question_type'] === 'fill_in_blank')
                                    <div class="p-3 rounded-lg {{ $isCorrect ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                                        <div class="flex items-center">
                                            @if($isCorrect)
                                                <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                <svg class="h-5 w-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                            <span class="{{ $isCorrect ? 'text-green-800' : 'text-red-800' }}">
                                                {{ is_array($userAnswer) ? implode(', ', $userAnswer) : ($userAnswer ?? 'No answer provided') }}
                                            </span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            @if($question['question_type'] === 'multiple_choice' && !$isCorrect)
                                <div class="correct-answer mb-4">
                                    <p class="text-sm font-medium text-secondary-700 mb-1">Correct answer:</p>
                                    <div class="p-3 rounded-lg bg-green-50 border border-green-200">
                                        <div class="flex items-center">
                                            <svg class="h-5 w-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            @php
                                                $correctAnswers = $questionModel->correctAnswers();
                                            @endphp
                                            <span class="text-green-800">
                                                @foreach($correctAnswers as $i => $answer)
                                                    {{ $answer->answer_text }}{{ !$loop->last ? ', ' : '' }}
                                                @endforeach
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            
                            @if($explanations[$index])
                                <div class="explanation mt-4">
                                    <p class="text-sm font-medium text-secondary-700 mb-1">Explanation:</p>
                                    <div class="p-4 bg-secondary-50 border border-secondary-200 rounded-lg text-secondary-800">
                                        {{ $explanations[$index] }}
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
            
            <div class="action-buttons flex flex-wrap gap-4 mt-8">
                <button wire:click="resetQuiz" class="btn btn-secondary">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Retry Quiz
                </button>
                
                <a href="{{ $quiz->lesson ? route('lesson.show', $quiz->lesson->slug) : '#' }}" class="btn btn-primary">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path>
                    </svg>
                    Back to Lesson
                </a>
            </div>
        </div>
    @endif
</div>

@push('scripts')
<script>
    function quizTimer(initialTime) {
        return {
            timeRemaining: initialTime,
            timerInterval: null,
            
            startTimer() {
                if (this.timeRemaining <= 0) return;
                
                this.timerInterval = setInterval(() => {
                    this.timeRemaining--;
                    @this.updateTimer(this.timeRemaining);
                    
                    if (this.timeRemaining <= 0) {
                        clearInterval(this.timerInterval);
                    }
                }, 1000);
            },
            
            formatTime(seconds) {
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = seconds % 60;
                return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
            }
        }
    }
    
    window.addEventListener('showAlert', event => {
        alert(event.detail.message);
    });
</script>
@endpush
