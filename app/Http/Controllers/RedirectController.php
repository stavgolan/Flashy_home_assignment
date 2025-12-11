<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Jobs\RecordHit;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    /**
     * Validates the link, records a hit, and redirects the user
     * @param Request $request
     * @param string $slug
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function __invoke(Request $request, string $slug)
    {
        $link = Link::where('slug', $slug)->first();

        if (!$link) {
            return response()->json(['message' => 'Link not found'], 404);
        }

        if (!$link->is_active) {
            return response()->json(['message' => 'Link is inactive'], 410);
        }

        // Dispatch async job
        RecordHit::dispatch(
            $link->id,
            $request->ip(),
            $request->userAgent()
        );

        // Redirect
        return redirect()->away($link->target_url);
    }
}
