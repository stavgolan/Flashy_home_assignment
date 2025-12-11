<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateLinkTest extends TestCase
{
    use RefreshDatabase;

    private function apiHeaders()
    {
        return ['X-Api-Key' => config('app.api_key')];
    }

    /** @test */
    public function it_creates_a_link_successfully()
    {
        $response = $this->postJson('/api/links', [
            'target_url' => 'https://google.com'
        ], $this->apiHeaders());

        $response->assertStatus(201);
        $response->assertJsonStructure(['slug', 'target_url', 'is_active']);
    }

    /** @test */
    public function it_requires_a_valid_url()
    {
        $response = $this->postJson('/api/links', [
            'target_url' => 'not-a-url'
        ], $this->apiHeaders());

        $response->assertStatus(422);
    }

    /** @test */
    public function it_creates_link_with_custom_slug()
    {
        $response = $this->postJson('/api/links', [
            'target_url' => 'https://google.com',
            'slug' => 'my_slug'
        ], $this->apiHeaders());

        $response->assertStatus(201);
        $this->assertDatabaseHas('links', ['slug' => 'my_slug']);
    }

    /** @test */
    public function slug_must_be_unique()
    {
        Link::factory()->create(['slug' => 'exists']);

        $response = $this->postJson('/api/links', [
            'target_url' => 'https://google.com',
            'slug' => 'exists'
        ], $this->apiHeaders());

        $response->assertStatus(422);
    }

    /** @test */
    public function it_rejects_invalid_api_key()
    {
        $response = $this->postJson('/api/links', [
            'target_url' => 'https://google.com',
        ], ['X-Api-Key' => 'wrong']);

        $response->assertStatus(401);
    }
}
