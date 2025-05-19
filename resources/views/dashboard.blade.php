<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h1 class="text-2xl font-bold text-primary-800 mb-4">Welcome to Sasarengan English!</h1>
                    <p class="text-secondary-600 mb-6">Improve your English speaking skills with our interactive lessons, quizzes, and more.</p>
                    
                    <div class="mt-4">
                        <a href="{{ route('learn.modules') }}" wire:navigate class="btn btn-primary flex items-center">
                            <span>Browse Learning Modules</span>
                            <svg class="h-5 w-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Recent Modules</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach(App\Models\Module::where('is_published', true)->orderBy('order')->limit(3)->get() as $module)
                        <div class="card hover:shadow-md transition-all duration-300">
                            <a href="{{ route('module.show', $module->slug) }}" wire:navigate class="block">
                                <div class="relative">
                                    @if($module->thumbnail)
                                        <div class="aspect-w-16 aspect-h-9">
                                            <img src="{{ $module->thumbnail }}" alt="{{ $module->title }}" class="object-cover w-full h-full">
                                        </div>
                                    @else
                                        <div class="aspect-w-16 aspect-h-9 bg-gradient-to-r from-primary-500 to-primary-700"></div>
                                    @endif
                                    <div class="absolute top-3 left-3">
                                        <div class="px-3 py-1 rounded-full text-xs font-medium bg-primary-600 text-white">
                                            {{ ucfirst($module->level ?? 'Beginner') }} Level
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">{{ $module->title }}</h3>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm line-clamp-2 mb-3">{{ Str::limit($module->description, 100) }}</p>
                                    
                                    <div class="flex items-center justify-between mt-2">
                                        <div class="flex space-x-4">
                                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"></path>
                                                    <path d="M10 5a1 1 0 100 2 1 1 0 000-2zm0 3a1 1 0 00-1 1v3a1 1 0 002 0V9a1 1 0 00-1-1z"></path>
                                                </svg>
                                                <span>{{ $module->duration_minutes ?? '45' }} min</span>
                                            </div>
                                            <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                                                <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span>{{ $module->lessons()->count() }} lessons</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Your Learning Journey</h2>
                    
                    @if(auth()->check())
                        <div class="stats grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="stat bg-primary-50 dark:bg-primary-900 p-4 rounded-lg">
                                <div class="stat-title text-primary-600 dark:text-primary-400 font-medium">Modules Started</div>
                                <div class="stat-value text-3xl font-bold text-primary-800 dark:text-primary-200">
                                    {{ App\Models\UserProgress::where('user_id', auth()->id())->distinct('lesson_id')->count() }}
                                </div>
                            </div>
                            
                            <div class="stat bg-green-50 dark:bg-green-900 p-4 rounded-lg">
                                <div class="stat-title text-green-600 dark:text-green-400 font-medium">Lessons Completed</div>
                                <div class="stat-value text-3xl font-bold text-green-800 dark:text-green-200">
                                    {{ App\Models\UserProgress::where('user_id', auth()->id())->where('status', 'completed')->count() }}
                                </div>
                            </div>
                            
                            <div class="stat bg-purple-50 dark:bg-purple-900 p-4 rounded-lg">
                                <div class="stat-title text-purple-600 dark:text-purple-400 font-medium">Quizzes Passed</div>
                                <div class="stat-value text-3xl font-bold text-purple-800 dark:text-purple-200">
                                    {{ App\Models\UserProgress::where('user_id', auth()->id())->whereNotNull('quiz_id')->where('status', 'completed')->count() }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6">
                            <h3 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-2">Continue Learning</h3>
                            @php
                                $recentProgress = App\Models\UserProgress::where('user_id', auth()->id())
                                    ->whereNotNull('lesson_id')
                                    ->orderBy('last_accessed_at', 'desc')
                                    ->first();
                                
                                $recentLesson = $recentProgress ? App\Models\Lesson::find($recentProgress->lesson_id) : null;
                            @endphp
                            
                            @if($recentLesson)
                                <div class="bg-primary-50 dark:bg-primary-900 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="flex-1">
                                            <p class="text-primary-600 dark:text-primary-400 text-sm">Continue where you left off</p>
                                            <h4 class="text-primary-800 dark:text-primary-200 font-semibold">{{ $recentLesson->title }}</h4>
                                            
                                            @if($recentProgress->status === 'in_progress')
                                                <div class="mt-2 bg-white dark:bg-gray-700 rounded-full h-2">
                                                    <div class="bg-primary-600 h-2 rounded-full" style="width: {{ $recentLesson->getCompletionPercentage(auth()->id()) }}%"></div>
                                                </div>
                                                <p class="text-xs text-primary-600 dark:text-primary-400 mt-1">{{ $recentLesson->getCompletionPercentage(auth()->id()) }}% completed</p>
                                            @endif
                                        </div>
                                        
                                        <div class="ml-4">
                                            <a href="{{ route('lesson.show', $recentLesson->slug) }}" wire:navigate class="btn btn-primary">
                                                Continue
                                                <svg class="h-4 w-4 ml-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                    <p class="text-gray-600 dark:text-gray-400">You haven't started any lessons yet. Begin your learning journey today!</p>
                                    <div class="mt-2">
                                        <a href="{{ route('learn.modules') }}" wire:navigate class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 font-medium">
                                            Browse all modules →
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                            <p class="text-gray-600 dark:text-gray-400">Log in to track your progress and access all features.</p>
                            <div class="mt-2">
                                <a href="{{ route('login') }}" wire:navigate class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 font-medium">
                                    Log in →
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
