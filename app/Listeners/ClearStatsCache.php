<?php

namespace App\Listeners;

use App\Events\LinkHitRecorded;
use Illuminate\Support\Facades\Cache;

class ClearStatsCache
{
    /**
     * Clears the cached statistics for the link when a new hit is recorded
     * @param LinkHitRecorded $event
     * @return void
     */
    public function handle(LinkHitRecorded $event)
    {
        $key = "link_stats:{$event->linkId}";
        Cache::forget($key);
    }
}
