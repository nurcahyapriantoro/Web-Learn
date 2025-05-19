<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ $module->title }}
            </h2>
            <a href="{{ route('learn.modules') }}" wire:navigate class="text-sm text-primary-600 hover:text-primary-800">
                <i class="fas fa-arrow-left mr-1"></i> Back to Courses
            </a>
        </div>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-md rounded-xl">
                <!-- Module Header -->
                <div class="relative">
                    @if($module->thumbnail)
                        <div class="aspect-w-16 aspect-h-6 sm:aspect-h-4">
                            <img src="{{ $module->thumbnail }}" alt="{{ $module->title }}" class="object-cover w-full h-full rounded-t-xl">
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent rounded-t-xl"></div>
                    <div class="absolute bottom-0 p-6">
                        <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-primary-600 text-white mb-4">
                            {{ ucfirst($module->level ?? 'Beginner') }} Level
                        </div>
                        <h1 class="text-4xl font-bold text-white mb-2">{{ $module->title }}</h1>
                    </div>
                </div>
                
                <!-- Module Content -->
                <div class="p-6">
                    <div class="flex flex-wrap gap-6 items-center justify-between mb-8">
                        <div class="flex items-center space-x-6">
                            <div class="flex items-center text-secondary-700">
                                <svg class="h-5 w-5 text-primary-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"></path>
                                    <path d="M10 5a1 1 0 100 2 1 1 0 000-2zm0 3a1 1 0 00-1 1v3a1 1 0 002 0V9a1 1 0 00-1-1z"></path>
                                </svg>
                                <span>{{ $module->duration_minutes ?? '45' }} minutes</span>
                            </div>
                            <div class="flex items-center text-secondary-700">
                                <svg class="h-5 w-5 text-primary-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                </svg>
                                <span>{{ $lessons->count() }} lessons</span>
                            </div>
                        </div>
                        
                        <a href="{{ route('learn.modules') }}" wire:navigate class="btn btn-secondary flex items-center">
                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                            <span>All Modules</span>
                        </a>
                    </div>
                    
                    <div class="prose prose-primary max-w-none mb-8">
                        {!! $module->description !!}
                    </div>
                    
                    <div class="border-t border-gray-200 pt-8">
                        <h2 class="text-2xl font-bold text-primary-800 mb-6">Module Lessons</h2>
                        
                        <div class="space-y-4">
                            @foreach($lessons as $lesson)
                                <div class="lesson-card p-4 bg-white border border-gray-200 rounded-lg hover:border-primary-300 hover:shadow-md transition-all duration-300">
                                    <a href="{{ route('lesson.show', $lesson->slug) }}" wire:navigate class="flex flex-col sm:flex-row items-start gap-4">
                                        <div class="flex-shrink-0">
                                            <div class="w-12 h-12 bg-primary-100 text-primary-800 rounded-full flex items-center justify-center border border-primary-200">
                                                <span class="text-lg font-bold">{{ $loop->iteration }}</span>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-lg font-semibold text-secondary-900 hover:text-primary-700 transition-colors">{{ $lesson->title }}</h3>
                                            <p class="text-secondary-600 text-sm line-clamp-2 mt-1">{{ $lesson->description }}</p>
                                            
                                            <div class="flex items-center mt-2 space-x-4">
                                                <div class="flex items-center text-xs text-secondary-500">
                                                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"></path>
                                                        <path d="M10 5a1 1 0 100 2 1 1 0 000-2zm0 3a1 1 0 00-1 1v3a1 1 0 002 0V9a1 1 0 00-1-1z"></path>
                                                    </svg>
                                                    <span>{{ $lesson->duration_minutes ?? '20' }} min</span>
                                                </div>
                                                
                                                <div class="flex items-center text-xs">
                                                    @if($lesson->type === 'video')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            Video
                                                        </span>
                                                    @elseif($lesson->type === 'text')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            Text
                                                        </span>
                                                    @elseif($lesson->type === 'zoom')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zm12.553 1.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                                                            </svg>
                                                            Zoom
                                                        </span>
                                                    @elseif($lesson->type === 'mixed')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                                                            <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                                            </svg>
                                                            Mixed
                                                        </span>
                                                    @endif
                                                </div>
                                                
                                                @if($lesson->is_free)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Free
                                                    </span>
                                                @endif
                                                
                                                @if($lesson->quiz()->exists())
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                                                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm8.707 4.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        Quiz
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 ml-auto">
                                            <svg class="h-5 w-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout> 