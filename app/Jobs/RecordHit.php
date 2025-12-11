<?php

namespace App\Jobs;

use App\Models\LinkHit;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Event;
use App\Events\LinkHitRecorded;

class RecordHit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $linkId;
    public string $ip;
    public ?string $userAgent;
    public function __construct(int $linkId, string $ip, ?string $userAgent)
    {
        $this->linkId = $linkId;
        $this->ip = $ip;
        $this->userAgent = $userAgent;
    }

    /**
     * Stores the link hit in the DB and triggers an event to clear cached statistics
     * @return void
     */
    public function handle()
    {
        LinkHit::create([
            'link_id' => $this->linkId,
            'ip' => $this->partialIp($this->ip),
            'user_agent' => $this->userAgent,
            'created_at' => now(),
        ]);

        // Fire event for cache clearing
        event(new LinkHitRecorded($this->linkId));
    }

    /**
     * Returns partially anonymized IP by replacing the last segment with '*'
     * @param string $ip
     * @return array|string|null
     */
    private function partialIp(string $ip): string
    {
        // For statistics: keep the first 2 parts of the IP and replace the last
        return preg_replace('/\.\d+$/', '.*', $ip);
    }
}
