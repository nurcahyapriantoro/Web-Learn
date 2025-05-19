<div class="video-player-container relative bg-white rounded-xl shadow-md overflow-hidden" x-data="videoPlayerHandler()">
    <div class="aspect-w-16 aspect-h-9 bg-secondary-900 relative">
        <!-- Video Element -->
        <video 
            x-ref="videoElement"
            class="w-full h-full object-cover"
            :src="$wire.videoUrl"
            :poster="$wire.lesson.thumbnail"
            @timeupdate="onTimeUpdate"
            @ended="onVideoEnded"
            @loadedmetadata="onVideoLoaded"
            @error="handleVideoError">
            
            <!-- Subtitles -->
            <track 
                v-if="$wire.subtitleUrl && $wire.showSubtitles" 
                kind="subtitles" 
                src="{{ $subtitleUrl }}" 
                srclang="id" 
                label="Indonesian"
                :default="$wire.showSubtitles">
            Your browser does not support the video tag.
        </video>
        
        <!-- Play/Pause Overlay Button -->
        <div 
            class="absolute inset-0 flex items-center justify-center cursor-pointer"
            x-show="!isPlaying"
            @click="togglePlay()">
            <div class="rounded-full bg-primary-600 bg-opacity-70 p-6 transform hover:scale-110 transition-all duration-300">
                <svg class="h-12 w-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z"></path>
                </svg>
            </div>
        </div>

        <!-- Video Error Message -->
        <div 
            class="absolute inset-0 flex flex-col items-center justify-center bg-secondary-900 bg-opacity-90 p-6"
            x-show="videoError"
            x-cloak>
            <svg class="h-16 w-16 text-red-500 mb-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <h3 class="text-xl font-bold text-white mb-2">Video Loading Error</h3>
            <p class="text-white text-center mb-4" x-text="videoErrorMessage || 'There was an error loading the video. Please try again.'"></p>
            <p class="text-sm text-gray-400">Video URL: {{ $videoUrl }}</p>
            <button @click="retryVideo()" class="mt-4 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors">
                Retry Loading
            </button>
        </div>
    </div>
    
    <!-- Video Controls -->
    <div class="video-controls bg-white p-3 border-t border-gray-100">
        <!-- Progress Bar -->
        <div class="relative h-2 bg-gray-200 rounded-full mb-3 cursor-pointer" 
            x-ref="progressBar"
            @click="seek($event)">
            <div class="absolute top-0 left-0 h-full bg-primary-500 rounded-full transition-all"
                :style="`width: ${progressPercentage}%`"></div>
        </div>
        
        <div class="flex justify-between items-center">
            <!-- Left Controls -->
            <div class="flex items-center space-x-3">
                <!-- Play/Pause Button -->
                <button @click="togglePlay()" class="text-secondary-800 hover:text-primary-600 focus:outline-none transition-colors">
                    <svg x-show="!isPlaying" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z"></path>
                    </svg>
                    <svg x-show="isPlaying" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"></path>
                    </svg>
                </button>
                
                <!-- Volume Control -->
                <div class="flex items-center space-x-1" x-data="{ showVolumeSlider: false }">
                    <button @click="toggleMute()" class="text-secondary-800 hover:text-primary-600 focus:outline-none transition-colors">
                        <svg x-show="!isMuted && $wire.volume > 50" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02zM14 3.23v2.06c2.89.86 5 3.54 5 6.71s-2.11 5.85-5 6.71v2.06c4.01-.91 7-4.49 7-8.77s-2.99-7.86-7-8.77z"></path>
                        </svg>
                        <svg x-show="!isMuted && $wire.volume > 0 && $wire.volume <= 50" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"></path>
                        </svg>
                        <svg x-show="isMuted || $wire.volume === 0" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"></path>
                        </svg>
                    </button>
                    
                    <div class="w-16 hidden sm:block">
                        <input type="range" min="0" max="100" step="1" 
                            x-model="volume"
                            @input="setVolume(volume)" 
                            class="w-full h-1 bg-gray-300 rounded-lg appearance-none cursor-pointer range-slider">
                    </div>
                </div>
                
                <!-- Time Display -->
                <div class="text-sm text-secondary-700">
                    <span x-text="formatTime(currentTime)"></span>
                    <span>/</span>
                    <span x-text="formatTime(duration)"></span>
                </div>
            </div>
            
            <!-- Right Controls -->
            <div class="flex items-center space-x-3">
                <!-- Subtitles Toggle -->
                <button @click="toggleSubtitles()" class="text-secondary-800 hover:text-primary-600 focus:outline-none transition-colors" x-show="$wire.subtitleUrl">
                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" :class="{'text-primary-600': $wire.showSubtitles}">
                        <path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zM4 12h4v2H4v-2zm10 6H4v-2h10v2zm6 0h-4v-2h4v2zm0-4H10v-2h10v2z"></path>
                    </svg>
                </button>
                
                <!-- Playback Speed -->
                <div class="relative" x-data="{ showSpeedOptions: false }">
                    <button @click="showSpeedOptions = !showSpeedOptions" type="button" class="text-sm font-medium text-secondary-800 hover:text-primary-600 focus:outline-none transition-colors">
                        <span x-text="`${playbackRate}x`"></span>
                    </button>
                    
                    <div x-show="showSpeedOptions" @click.away="showSpeedOptions = false" class="absolute right-0 bottom-8 bg-white rounded-lg shadow-md py-1 z-10">
                        <div class="flex flex-col text-sm">
                            <button @click="setPlaybackRate(0.5); showSpeedOptions = false" class="px-4 py-1 text-left hover:bg-primary-50 transition-colors" :class="{'text-primary-600 font-medium': playbackRate === 0.5}">0.5x</button>
                            <button @click="setPlaybackRate(0.75); showSpeedOptions = false" class="px-4 py-1 text-left hover:bg-primary-50 transition-colors" :class="{'text-primary-600 font-medium': playbackRate === 0.75}">0.75x</button>
                            <button @click="setPlaybackRate(1.0); showSpeedOptions = false" class="px-4 py-1 text-left hover:bg-primary-50 transition-colors" :class="{'text-primary-600 font-medium': playbackRate === 1.0}">1.0x</button>
                            <button @click="setPlaybackRate(1.25); showSpeedOptions = false" class="px-4 py-1 text-left hover:bg-primary-50 transition-colors" :class="{'text-primary-600 font-medium': playbackRate === 1.25}">1.25x</button>
                            <button @click="setPlaybackRate(1.5); showSpeedOptions = false" class="px-4 py-1 text-left hover:bg-primary-50 transition-colors" :class="{'text-primary-600 font-medium': playbackRate === 1.5}">1.5x</button>
                            <button @click="setPlaybackRate(2.0); showSpeedOptions = false" class="px-4 py-1 text-left hover:bg-primary-50 transition-colors" :class="{'text-primary-600 font-medium': playbackRate === 2.0}">2.0x</button>
                        </div>
                    </div>
                </div>
                
                <!-- Fullscreen Toggle -->
                <button @click="toggleFullscreen()" class="text-secondary-800 hover:text-primary-600 focus:outline-none transition-colors">
                    <svg x-show="!isFullscreen" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"></path>
                    </svg>
                    <svg x-show="isFullscreen" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M5 16h3v3h2v-5H5v2zm3-8H5v2h5V5H8v3zm6 11h2v-3h3v-2h-5v5zm2-11V5h-2v5h5V8h-3z"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function videoPlayerHandler() {
        return {
            isPlaying: false,
            isMuted: @entangle('isMuted'),
            playbackRate: @entangle('playbackRate'),
            volume: @entangle('volume'),
            currentTime: @entangle('currentTime'),
            duration: 0,
            isFullscreen: false,
            progressPercentage: 0,
            videoError: false,
            videoErrorMessage: '',
            
            init() {
                this.$nextTick(() => {
                    const video = this.$refs.videoElement;
                    
                    // Set initial properties
                    video.volume = this.volume / 100;
                    video.playbackRate = this.playbackRate;
                    
                    // Ensure currentTime is an integer and > 0
                    this.currentTime = parseInt(this.currentTime || 0);
                    if (this.currentTime > 0) {
                        video.currentTime = this.currentTime;
                    }
                    
                    console.log('Video player initialized', {
                        videoUrl: @js($videoUrl),
                        currentTime: this.currentTime,
                        volume: this.volume,
                        playbackRate: this.playbackRate
                    });
                });
            },
            
            onVideoLoaded() {
                const video = this.$refs.videoElement;
                this.duration = Math.round(video.duration || 0);
                console.log('Video loaded successfully', {
                    duration: this.duration,
                    videoWidth: video.videoWidth,
                    videoHeight: video.videoHeight
                });
                this.videoError = false;
            },
            
            handleVideoError(event) {
                const video = this.$refs.videoElement;
                const errorCode = video.error ? video.error.code : 0;
                const errorMessage = video.error ? video.error.message : 'Unknown error';
                
                this.videoError = true;
                this.videoErrorMessage = `Error code ${errorCode}: ${errorMessage}`;
                console.error('Video loading error', {
                    code: errorCode,
                    message: errorMessage,
                    videoUrl: @js($videoUrl)
                });
            },
            
            retryVideo() {
                const video = this.$refs.videoElement;
                this.videoError = false;
                video.load();
                console.log('Retrying video load');
            },
            
            togglePlay() {
                const video = this.$refs.videoElement;
                if (video.paused) {
                    video.play();
                    this.isPlaying = true;
                } else {
                    video.pause();
                    this.isPlaying = false;
                }
                @this.togglePlay();
            },
            
            toggleMute() {
                const video = this.$refs.videoElement;
                video.muted = !video.muted;
                this.isMuted = video.muted;
                @this.toggleMute();
            },
            
            setPlaybackRate(rate) {
                const video = this.$refs.videoElement;
                video.playbackRate = rate;
                this.playbackRate = rate;
                @this.setPlaybackRate(rate);
            },
            
            setVolume(volumeLevel) {
                const video = this.$refs.videoElement;
                video.volume = volumeLevel / 100;
                @this.setVolume(volumeLevel);
            },
            
            toggleSubtitles() {
                @this.toggleSubtitles();
            },
            
            onTimeUpdate() {
                const video = this.$refs.videoElement;
                this.currentTime = Math.floor(video.currentTime || 0);
                this.duration = Math.round(video.duration || 0);
                this.progressPercentage = this.duration > 0 ? (video.currentTime / video.duration) * 100 : 0;
                @this.dispatch('timeupdate', Math.floor(video.currentTime || 0));
            },
            
            onVideoEnded() {
                this.isPlaying = false;
                @this.dispatch('videoEnded');
            },
            
            seek(event) {
                const progressBar = this.$refs.progressBar;
                const video = this.$refs.videoElement;
                
                if (!video.duration) return;
                
                const position = (event.offsetX / progressBar.offsetWidth);
                const seekTime = position * video.duration;
                
                video.currentTime = seekTime;
                @this.seek(Math.floor(seekTime));
            },
            
            toggleFullscreen() {
                const container = this.$el;
                
                if (!document.fullscreenElement) {
                    container.requestFullscreen().then(() => {
                        this.isFullscreen = true;
                    }).catch(err => {
                        console.log(`Error attempting to enable fullscreen: ${err.message}`);
                    });
                } else {
                    document.exitFullscreen().then(() => {
                        this.isFullscreen = false;
                    });
                }
            },
            
            formatTime(seconds) {
                seconds = parseInt(seconds || 0);
                if (isNaN(seconds) || seconds === 0) return '0:00';
                
                const minutes = Math.floor(seconds / 60);
                const remainingSeconds = Math.floor(seconds % 60);
                
                return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
            }
        }
    }
</script>

<style>
    /* Custom range slider styling */
    .range-slider {
        -webkit-appearance: none;
        height: 6px;
        border-radius: 3px;
        background: #e2e8f0;
        outline: none;
    }
    
    .range-slider::-webkit-slider-thumb {
        -webkit-appearance: none;
        appearance: none;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #0A84FF;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .range-slider::-webkit-slider-thumb:hover {
        transform: scale(1.2);
    }
    
    .range-slider::-moz-range-thumb {
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: #0A84FF;
        cursor: pointer;
        transition: all 0.3s ease;
        border: none;
    }
    
    .range-slider::-moz-range-thumb:hover {
        transform: scale(1.2);
    }
    
    /* Fix for Alpine.js x-show with x-cloak to prevent flash */
    [x-cloak] { display: none !important; }
</style>
@endpush
