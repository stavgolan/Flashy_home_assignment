<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LinkHitRecorded
{
    // Event triggered after a link hit is recorded
    use Dispatchable, SerializesModels;

    public int $linkId;

    public function __construct(int $linkId)
    {
        $this->linkId = $linkId;
    }
}
