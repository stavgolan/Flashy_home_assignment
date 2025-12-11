<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Link;
use App\Models\LinkHit;
use Illuminate\Foundation\Testing\RefreshDatabase;

class StatsTest extends TestCase
{
    use RefreshDatabase;

    private function apiHeaders()
    {
        return ['X-Api-Key' => config('app.api_key')];
    }

    /** @test */
    public function it_returns_stats_for_a_link()
    {
        $link = Link::factory()->create();

        LinkHit::factory()->count(3)->create([
            'link_id' => $link->id,
        ]);

        $response = $this->getJson("/api/links/{$link->slug}/stats", $this->apiHeaders());

        $response->assertStatus(200);
        $response->assertJson([
            'slug' => $link->slug,
            'total_hits' => 3,
        ]);
    }

    /** @test */
    public function it_limits_last_hits_to_five()
    {
        $link = Link::factory()->create();

        LinkHit::factory()->count(8)->create([
            'link_id' => $link->id,
        ]);

        $response = $this->getJson("/api/links/{$link->slug}/stats", $this->apiHeaders());

        $this->assertCount(5, $response->json('last_hits'));
    }
}
