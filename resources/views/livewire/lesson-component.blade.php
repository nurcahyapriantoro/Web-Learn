<div class="lesson-component max-w-7xl mx-auto px-4 py-8 sm:px-6 lg:px-8">
    <div class="lesson-header mb-8">
        <!-- Module & Lesson Title -->
        <div class="mb-6">
            <a href="{{ route('module.show', $module->slug) }}" class="text-primary-600 hover:text-primary-800 transition-colors duration-300 flex items-center mb-2">
                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                <span>{{ $module->title }}</span>
            </a>
            <h1 class="text-3xl font-bold text-primary-800 mb-2">{{ $lesson->title }}</h1>
            
            <!-- Completed Badge -->
            @if($isCompleted)
                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 mb-4">
                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span>Completed</span>
                </div>
            @endif
        </div>
        
        <!-- Navigation Pills -->
        <div class="flex flex-wrap items-center gap-2 mb-6">
            <!-- Lesson Info Tab -->
            <button type="button" class="px-4 py-2 rounded-full bg-primary-600 text-white font-medium text-sm shadow-sm transform transition-all duration-300 hover:scale-105" onclick="showTab('info')">
                Lesson Info
            </button>
            
            <!-- Video Tab -->
            @if($showVideoPlayer)
                <button type="button" class="px-4 py-2 rounded-full bg-secondary-100 text-secondary-800 font-medium text-sm shadow-sm transform transition-all duration-300 hover:scale-105 hover:bg-secondary-200" onclick="showTab('video')">
                    <svg class="inline-block h-4 w-4 -mt-0.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                    </svg>
                    Video
                </button>
            @endif
            
            <!-- Quiz Tab -->
            @if($showQuiz)
                <button type="button" class="px-4 py-2 rounded-full bg-secondary-100 text-secondary-800 font-medium text-sm shadow-sm transform transition-all duration-300 hover:scale-105 hover:bg-secondary-200" onclick="showTab('quiz')">
                    <svg class="inline-block h-4 w-4 -mt-0.5 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                        <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                    </svg>
                    Quiz
                </button>
            @endif
        </div>
    </div>
    
    <!-- Lesson Content Tabs -->
    <div class="lesson-content mb-8">
        <!-- Lesson Info Tab Content -->
        <div id="tab-info" class="tab-content animate-fade-in">
            <div class="bg-white rounded-xl shadow-md overflow-hidden p-6">
                @if($lesson->type !== 'zoom')
                    <div class="lesson-description prose prose-primary max-w-none mb-6">
                        {!! $lesson->description !!}
                    </div>
                @endif
                
                @if($lesson->type === 'text' || $lesson->type === 'mixed')
                    <div class="lesson-content prose prose-primary max-w-none text-secondary-800 mb-6">
                        {!! $lesson->content !!}
                    </div>
                @endif
                
                @if($lesson->type === 'zoom')
                    <div class="zoom-meeting bg-primary-50 rounded-lg p-6 mb-6">
                        <h3 class="text-xl font-semibold text-primary-800 mb-3">Zoom Meeting Details</h3>
                        <p class="text-secondary-700 mb-4">{{ $lesson->description }}</p>
                        
                        <div class="zoom-info flex flex-col md:flex-row gap-6">
                            <div class="flex-1">
                                <div class="bg-white rounded-lg shadow-sm p-4 flex flex-col items-center text-center">
                                    <svg class="h-12 w-12 text-blue-500 mb-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 4.5v15c0 .85-.65 1.5-1.5 1.5H21V7.387l-9 6.463-9-6.463V21H1.5C.649 21 0 20.35 0 19.5v-15C0 3.649.649 3 1.5 3h21c.85 0 1.5.649 1.5 1.5z"></path>
                                        <path d="M12 14.304L22.425 6H1.575L12 14.304z"></path>
                                    </svg>
                                    <h4 class="font-medium text-secondary-900 mb-1">Meeting Link</h4>
                                    <a href="{{ $lesson->zoom_link }}" target="_blank" class="btn btn-primary mt-2 px-5 py-2">
                                        Join Meeting
                                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            
                            @if($lesson->duration_minutes)
                                <div class="flex-1">
                                    <div class="bg-white rounded-lg shadow-sm p-4 flex flex-col items-center text-center">
                                        <svg class="h-12 w-12 text-blue-500 mb-2" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 2C6.486 2 2 6.486 2 12s4.486 10 10 10 10-4.486 10-10S17.514 2 12 2zm0 18c-4.411 0-8-3.589-8-8s3.589-8 8-8 8 3.589 8 8-3.589 8-8 8z"></path>
                                            <path d="M13 7h-2v5.414l3.293 3.293 1.414-1.414L13 11.586z"></path>
                                        </svg>
                                        <h4 class="font-medium text-secondary-900 mb-1">Meeting Duration</h4>
                                        <p class="text-xl font-bold text-primary-700">{{ $lesson->duration_minutes }} minutes</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
                
                @if(!$isCompleted)
                    <div class="mt-6">
                        <button wire:click="markAsCompleted" class="btn btn-primary">
                            <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Mark as Completed
                        </button>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Video Tab Content -->
        @if($showVideoPlayer)
            <div id="tab-video" class="tab-content hidden animate-fade-in">
                @livewire('video-player', ['lesson' => $lesson])
            </div>
        @endif
        
        <!-- Quiz Tab Content -->
        @if($showQuiz)
            <div id="tab-quiz" class="tab-content hidden animate-fade-in">
                @livewire('quiz-component', ['quiz' => $lesson->quiz])
            </div>
        @endif
    </div>
    
    <!-- Next/Prev Navigation -->
    <div class="lesson-navigation flex justify-between mt-8">
        @if($nextPrevLessons['previous'])
            <a href="{{ route('lesson.show', $nextPrevLessons['previous']['slug']) }}" class="btn btn-secondary">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Previous Lesson
            </a>
        @else
            <div></div>
        @endif
        
        @if($nextPrevLessons['next'])
            <a href="{{ route('lesson.show', $nextPrevLessons['next']['slug']) }}" class="btn btn-primary">
                Next Lesson
                <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </a>
        @endif
    </div>
</div>

@push('scripts')
<script>
    function showTab(tabName) {
        // Hide all tab content
        document.querySelectorAll('.tab-content').forEach(tab => {
            tab.classList.add('hidden');
        });
        
        // Show the selected tab
        document.getElementById(`tab-${tabName}`).classList.remove('hidden');
        
        // Update active states for tab buttons
        document.querySelectorAll('.lesson-header button').forEach(button => {
            if (button.textContent.trim().toLowerCase().includes(tabName)) {
                button.classList.remove('bg-secondary-100', 'text-secondary-800');
                button.classList.add('bg-primary-600', 'text-white');
            } else {
                button.classList.remove('bg-primary-600', 'text-white');
                button.classList.add('bg-secondary-100', 'text-secondary-800');
            }
        });
    }
    
    window.addEventListener('showLoginPrompt', () => {
        alert('Please log in to track your progress and mark lessons as completed.');
    });
    
    window.addEventListener('lessonCompleted', () => {
        // You could show a congratulations message or update UI
        console.log('Lesson marked as completed!');
    });
</script>
@endpush
