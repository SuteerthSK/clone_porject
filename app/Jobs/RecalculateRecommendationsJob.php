<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class RecalculateRecommendationsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $userId;

    public function __construct($userId) { $this->userId = $userId; }

    public function handle()
    {
        // Stub: compute and cache recommendations.
        $recommendations = []; // populate with book IDs
        Cache::put("recommendations:user:{$this->userId}", $recommendations, 3600);
    }
}
