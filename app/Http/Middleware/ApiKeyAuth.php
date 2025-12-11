<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyAuth
{
    /**
     * @param mixed $request
     * @param Closure $next
     */
    public function handle($request, Closure $next)
    {
        $apiKey = $request->header('X-Api-Key');

        // Compare to .env value
        if (!$apiKey || $apiKey !== config('app.api_key')) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }

}
