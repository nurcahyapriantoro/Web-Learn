<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-primary-800 mb-2">{{ $quiz->title }}</h1>
                <p class="text-secondary-600">{{ $quiz->description }}</p>
                
                @if($quiz->lesson)
                    <div class="mt-4">
                        <a href="{{ route('lesson.show', $quiz->lesson->slug) }}" class="text-primary-600 hover:text-primary-800 transition-colors duration-300 flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            <span>Back to {{ $quiz->lesson->title }}</span>
                        </a>
                    </div>
                @endif
            </div>
            
            @livewire('quiz-component', ['quiz' => $quiz])
        </div>
    </div>
</x-app-layout> 