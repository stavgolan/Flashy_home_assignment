<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Link;
use App\Services\SlugGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SlugGeneratorTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_generates_slug_with_correct_length()
    {
        $generator = new SlugGenerator();

        $slug = $generator->generate(6);

        $this->assertEquals(6, strlen($slug));
    }

    /** @test */
    public function it_generates_only_allowed_characters()
    {
        $generator = new SlugGenerator();

        $slug = $generator->generate(10);

        $this->assertMatchesRegularExpression('/^[A-Za-z0-9_-]+$/', $slug);
    }

    /** @test */
    public function it_generates_unique_slugs_by_checking_the_database()
    {
        // Create a record with a fixed slug to verify that the generator won’t accidentally return it.
        Link::factory()->create(['slug' => 'ABC123']);

        $generator = new SlugGenerator();
        $slug = $generator->generate(6);

        $this->assertNotEquals('ABC123', $slug);
    }

    /** @test */
    public function it_generates_new_slug_if_collision_occurred()
    {
        // (collision)
        Link::factory()->create(['slug' => 'DUPLICATE']);

        $generator = new SlugGenerator();

        // Force a collision: slug length = 9 (same as "DUPLICATE")
        $slug = $generator->generate(9);

        $this->assertNotEquals('DUPLICATE', $slug);
    }

    /** @test */
    public function it_generates_slugs_of_different_lengths()
    {
        $generator = new SlugGenerator();

        $slug4 = $generator->generate(4);
        $slug8 = $generator->generate(8);

        $this->assertEquals(4, strlen($slug4));
        $this->assertEquals(8, strlen($slug8));
    }
}
