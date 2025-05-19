<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
        
        <!-- FontAwesome icons (add if needed) -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-50">
            <livewire:layout.navigation />

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow-sm animate-fade-in">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8 animate-slide-up">
                {{ $slot }}
            </main>
            
            <!-- Footer -->
            <footer class="bg-primary-800 text-white mt-auto">
                <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                        <div class="col-span-1 md:col-span-2">
                            <h3 class="text-xl font-bold mb-4 text-white">{{ config('app.name', 'Sasarengan English Learning') }}</h3>
                            <p class="text-primary-100 mb-4">Providing high-quality learning experiences through interactive content and modern educational technology.</p>
                            <div class="flex space-x-4">
                                <a href="https://www.instagram.com/sasarengan_english/" target="_blank" aria-label="Instagram" class="text-primary-200 hover:text-white transition-colors duration-300">
                                    <i class="fab fa-instagram text-xl"></i>
                                </a>
                                <a href="https://www.tiktok.com/@sasarengan_english" target="_blank" aria-label="TikTok" class="text-primary-200 hover:text-white transition-colors duration-300">
                                    <i class="fab fa-tiktok text-xl"></i>
                                </a>
                                <a href="mailto:sasarengan.id@gmail.com" target="_blank" aria-label="Email" class="text-primary-200 hover:text-white transition-colors duration-300">
                                    <i class="fas fa-envelope text-xl"></i>
                                </a>
                            </div>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold mb-4 text-white">Quick Links</h4>
                            <ul class="space-y-2">
                                <li><a href="{{ route('dashboard') }}" wire:navigate class="text-primary-200 hover:text-white transition-colors duration-300">Home</a></li>
                                <li><a href="{{ route('learn.modules') }}" wire:navigate class="text-primary-200 hover:text-white transition-colors duration-300">Courses</a></li>
                                <li><a href="{{ route('profile') }}" wire:navigate class="text-primary-200 hover:text-white transition-colors duration-300">Profile</a></li>
                                <li><a href="#" class="text-primary-200 hover:text-white transition-colors duration-300">Contact</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-lg font-bold mb-4 text-white">Contact Us</h4>
                            <ul class="space-y-2">
                                <li class="flex items-start space-x-2">
                                    <i class="fas fa-map-marker-alt text-primary-300 mt-1"></i>
                                    <span class="text-primary-100">Sasarengan English Learning</span>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <i class="fas fa-envelope text-primary-300 mt-1"></i>
                                    <a href="mailto:sasarengan.id@gmail.com" class="text-primary-100 hover:text-white transition-colors duration-300">sasarengan.id@gmail.com</a>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <i class="fab fa-instagram text-primary-300 mt-1"></i>
                                    <a href="https://www.instagram.com/sasarengan_english/" target="_blank" class="text-primary-100 hover:text-white transition-colors duration-300">@sasarengan_english</a>
                                </li>
                                <li class="flex items-start space-x-2">
                                    <i class="fab fa-tiktok text-primary-300 mt-1"></i>
                                    <a href="https://www.tiktok.com/@sasarengan_english" target="_blank" class="text-primary-100 hover:text-white transition-colors duration-300">@sasarengan_english</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="border-t border-primary-700 mt-8 pt-8 text-center">
                        <p class="text-primary-200">&copy; {{ date('Y') }} {{ config('app.name', 'Sasarengan English Learning') }}. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>
        
        <!-- Custom scripts for animations -->
        <script>
            // Intersection Observer for reveal animations
            document.addEventListener('DOMContentLoaded', function() {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('animate-fade-in');
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.1
                });
                
                // Observe all elements with reveal class
                document.querySelectorAll('.reveal').forEach(element => {
                    observer.observe(element);
                });
            });
        </script>
    </body>
</html>
