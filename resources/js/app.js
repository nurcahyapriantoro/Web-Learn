import './bootstrap';

// Initialize Alpine.js for custom components
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Add event listeners for our custom video player
document.addEventListener('DOMContentLoaded', function() {
    // Listen for Livewire events related to video player
    window.addEventListener('toggleVideoPlayback', event => {
        const video = document.querySelector('video');
        if (video) {
            if (event.detail) {
                video.pause();
            } else {
                video.play();
            }
        }
    });

    window.addEventListener('toggleVideoMute', event => {
        const video = document.querySelector('video');
        if (video) {
            video.muted = event.detail;
        }
    });

    window.addEventListener('setVideoPlaybackRate', event => {
        const video = document.querySelector('video');
        if (video) {
            video.playbackRate = event.detail;
        }
    });

    window.addEventListener('setVideoVolume', event => {
        const video = document.querySelector('video');
        if (video) {
            video.volume = event.detail / 100;
        }
    });

    window.addEventListener('seekVideo', event => {
        const video = document.querySelector('video');
        if (video) {
            video.currentTime = event.detail;
        }
    });
});
