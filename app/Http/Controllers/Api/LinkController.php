<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Http\Requests\StoreLinkRequest;
use App\Models\Link;
use App\Services\SlugGenerator;
use Illuminate\Support\Facades\Cache;

class LinkController extends Controller
{
    /**
     * Creates a new shortened link after validating input and generating a unique slug
     * @param Requests\StoreLinkRequest $request
     * @param SlugGenerator $slugGenerator
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreLinkRequest $request, SlugGenerator $slugGenerator){
        $slug = $request->slug ?? $slugGenerator->generate();

        $link = Link::create([
            'slug' => $slug,
            'target_url' => $request->target_url,
            'is_active' => true,
        ]);

        return response()->json([
            'slug' => $slug,
            'target_url' => $request->target_url,
            'is_active' => true,
        ], 201);
    }

    /**
     * Returns cached link statistics (total hits and last hits), rebuilding the data if needed
     * @param string $slug
     */
    public function stats(string $slug)
    {
        $cacheKey = "link_stats:{$slug}";

        // If the data is in the cache will return it
        return Cache::remember($cacheKey, 60, function () use ($slug) {

            $link = Link::where('slug', $slug)->first();

            if (!$link) {
                return response()->json(['message' => 'Link not found'], 404);
            }

            $totalHits = $link->hits()->count();

            $lastHits = $link->hits()
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get(['ip', 'created_at'])
                ->map(function ($hit) {
                    return [
                        'ip' => $hit->ip,
                        'created_at' => $hit->created_at,
                    ];
                });

            return response()->json([
                'slug' => $slug,
                'target_url' => $link->target_url,
                'total_hits' => $totalHits,
                'last_hits' => $lastHits,
            ]);
        });
    }
}
