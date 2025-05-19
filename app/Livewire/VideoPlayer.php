<?php

namespace App\Livewire;

use App\Models\Lesson;
use App\Models\User;
use App\Models\UserProgress;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VideoPlayer extends Component
{
    public Lesson $lesson;
    public string $videoUrl = '';
    public ?string $subtitleUrl = null;
    public int $currentTime = 0;
    public bool $showSubtitles = false;
    public bool $autoSaveProgress = true;
    public int $lastSavedTime = 0;
    
    // Video player settings
    public float $playbackRate = 1.0;
    public bool $isPaused = true;
    public bool $isMuted = false;
    public int $volume = 80;
    
    // Event listeners to interact with the JavaScript video player
    protected $listeners = [
        'timeupdate' => 'handleTimeUpdate',
        'videoEnded' => 'handleVideoEnded',
        'playbackRateChanged' => 'updatePlaybackRate',
        'subtitleToggled' => 'toggleSubtitles',
    ];
    
    public function mount(Lesson $lesson)
    {
        $this->lesson = $lesson;
        $this->videoUrl = $lesson->video_url ?? '';
        $this->subtitleUrl = $lesson->subtitle_url;
        $this->currentTime = 0; // Ensure default value
        
        // Load user's previous progress if available
        if (Auth::check()) {
            $progress = UserProgress::where('user_id', Auth::id())
                ->where('lesson_id', $lesson->id)
                ->whereNull('quiz_id')
                ->first();
                
            if ($progress && $progress->video_progress_seconds !== null) {
                $this->currentTime = (int)$progress->video_progress_seconds;
                $this->lastSavedTime = $this->currentTime;
            }
        }
    }
    
    public function handleTimeUpdate($time)
    {
        // Ensure $time is an integer
        $this->currentTime = (int)$time;
        
        // Auto-save progress every 10 seconds
        if ($this->autoSaveProgress && ($this->currentTime - $this->lastSavedTime) >= 10) {
            $this->saveProgress();
            $this->lastSavedTime = $this->currentTime;
        }
    }
    
    public function handleVideoEnded()
    {
        // Mark lesson as completed when video ends
        if (Auth::check()) {
            $this->saveProgress(true);
        }
    }
    
    public function saveProgress($completed = false)
    {
        if (!Auth::check()) {
            return;
        }
        
        $progress = UserProgress::firstOrNew([
            'user_id' => Auth::id(),
            'lesson_id' => $this->lesson->id,
            'quiz_id' => null,
        ]);
        
        $progress->video_progress_seconds = $this->currentTime;
        $progress->last_accessed_at = now();
        
        // If explicitly marked as completed or reached 95% of the video
        if ($completed || ($this->lesson->duration_minutes && 
            $this->currentTime >= ($this->lesson->duration_minutes * 60 * 0.95))) {
            $progress->status = 'completed';
            $progress->completed_at = $progress->completed_at ?? now();
        } else {
            $progress->status = 'in_progress';
        }
        
        $progress->save();
        
        $this->dispatch('progressUpdated', [
            'percentage' => $progress->video_percentage ?? 0,
            'status' => $progress->status,
        ]);
    }
    
    public function updatePlaybackRate($rate)
    {
        $this->playbackRate = (float)$rate;
    }
    
    public function toggleSubtitles()
    {
        $this->showSubtitles = !$this->showSubtitles;
    }
    
    public function togglePlay()
    {
        $this->isPaused = !$this->isPaused;
        $this->dispatch('toggleVideoPlayback', $this->isPaused);
    }
    
    public function toggleMute()
    {
        $this->isMuted = !$this->isMuted;
        $this->dispatch('toggleVideoMute', $this->isMuted);
    }
    
    public function setPlaybackRate($rate)
    {
        $this->playbackRate = (float)$rate;
        $this->dispatch('setVideoPlaybackRate', $this->playbackRate);
    }
    
    public function setVolume($volume)
    {
        $this->volume = (int)$volume;
        $this->dispatch('setVideoVolume', $this->volume);
    }
    
    public function seek($time)
    {
        $this->dispatch('seekVideo', (int)$time);
    }
    
    public function render()
    {
        return view('livewire.video-player');
    }
}
