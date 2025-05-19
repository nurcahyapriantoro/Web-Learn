<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Courses') }}
        </h2>
    </x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="text-center max-w-4xl mx-auto mb-12">
                <h1 class="text-4xl font-bold text-primary-800 mb-4">English Learning Modules</h1>
                <p class="text-lg text-secondary-600">Complete lessons, quizzes, and improve your English speaking skills</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($modules as $module)
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
                            
                            <div class="p-6">
                                <h2 class="text-xl font-bold text-secondary-900 mb-2 hover:text-primary-700 transition-colors">{{ $module->title }}</h2>
                                <p class="text-secondary-600 text-sm line-clamp-2 mb-4">{{ Str::limit($module->description, 120) }}</p>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex space-x-4">
                                        <div class="flex items-center text-xs text-secondary-500">
                                            <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 110-12 6 6 0 010 12z"></path>
                                                <path d="M10 5a1 1 0 100 2 1 1 0 000-2zm0 3a1 1 0 00-1 1v3a1 1 0 002 0V9a1 1 0 00-1-1z"></path>
                                            </svg>
                                            <span>{{ $module->duration_minutes ?? '45' }} min</span>
                                        </div>
                                        <div class="flex items-center text-xs text-secondary-500">
                                            <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                            </svg>
                                            <span>{{ $module->lessons()->count() }} lessons</span>
                                        </div>
                                    </div>
                                    
                                    <div class="transform transition-transform duration-300 hover:translate-x-1">
                                        <svg class="h-5 w-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout> 