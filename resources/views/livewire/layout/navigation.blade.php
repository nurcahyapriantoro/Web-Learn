<?php

use App\Livewire\Actions\Logout;

$logout = function (Logout $logout) {
    $logout();

    $this->redirect('/', navigate: true);
};

?>

<nav x-data="{ open: false }" class="bg-white shadow-sm border-b border-gray-100 sticky top-0 z-50 animate-fade-in">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate class="hover-expand inline-block">
                        <x-application-logo class="block h-10 w-auto fill-current text-primary-600" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-4 sm:-my-px sm:ms-8 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('learn.modules')" :active="request()->routeIs('learn.modules') || request()->routeIs('module.show') || request()->routeIs('lesson.show') || request()->routeIs('quiz.show')" wire:navigate>
                        {{ __('Courses') }}
                    </x-nav-link>

                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Notifications -->
                <button class="mr-4 text-secondary-600 hover:text-primary-600 transition-colors duration-300 relative">
                    <i class="fas fa-bell text-xl"></i>
                    <span class="absolute -top-1 -right-1 bg-primary-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">3</span>
                </button>

                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-gray-200 text-sm leading-4 font-medium rounded-lg text-secondary-800 bg-white hover:bg-gray-50 hover:border-primary-300 focus:outline-none focus:border-primary-400 focus:ring-2 focus:ring-primary-300 transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-2">
                                <svg class="fill-current h-4 w-4 text-secondary-600" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 text-sm text-secondary-700 border-b border-gray-200">
                            <div>{{ auth()->user()->name }}</div>
                            <div class="text-secondary-500 truncate">{{ auth()->user()->email }}</div>
                        </div>
                        
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            <i class="fas fa-user-circle mr-2 text-primary-500"></i>
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        
                        <x-dropdown-link href="#">
                            <i class="fas fa-cog mr-2 text-primary-500"></i>
                            {{ __('Settings') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                <i class="fas fa-sign-out-alt mr-2 text-primary-500"></i>
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-secondary-600 hover:text-primary-600 hover:bg-gray-50 focus:outline-none focus:text-primary-700 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                <i class="fas fa-home mr-2 text-primary-500"></i>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('learn.modules')" :active="request()->routeIs('learn.modules') || request()->routeIs('module.show') || request()->routeIs('lesson.show') || request()->routeIs('quiz.show')" wire:navigate>
                <i class="fas fa-book mr-2 text-primary-500"></i>
                {{ __('Courses') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('resources')" wire:navigate>
                <i class="fas fa-file-alt mr-2 text-primary-500"></i>
                {{ __('Resources') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('community')" wire:navigate>
                <i class="fas fa-users mr-2 text-primary-500"></i>
                {{ __('Community') }}
            </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-secondary-800" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name" x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-secondary-500">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    <i class="fas fa-user-circle mr-2 text-primary-500"></i>
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                
                <x-responsive-nav-link href="#">
                    <i class="fas fa-cog mr-2 text-primary-500"></i>
                    {{ __('Settings') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        <i class="fas fa-sign-out-alt mr-2 text-primary-500"></i>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
