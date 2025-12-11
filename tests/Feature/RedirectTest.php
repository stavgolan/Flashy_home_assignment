<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Link;
use Illuminate\Support\Facades\Queue;
use App\Jobs\RecordHit;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RedirectTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_redirects_to_target_url()
    {
        $link = Link::factory()->create([
            'slug' => 'abc123',
            'target_url' => 'https://google.com',
            'is_active' => true,
        ]);

        Queue::fake();

        $response = $this->get('/r/abc123');

        $response->assertStatus(302);
        $response->assertRedirect('https://google.com');

        Queue::assertPushed(RecordHit::class);
    }

    /** @test */
    public function it_returns_404_for_missing_slug()
    {
        $response = $this->get('/r/notfound');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_returns_410_if_link_is_inactive()
    {
        $link = Link::factory()->create([
            'slug' => 'dead',
            'is_active' => false,
        ]);

        $response = $this->get('/r/dead');

        $response->assertStatus(410);
    }
}
