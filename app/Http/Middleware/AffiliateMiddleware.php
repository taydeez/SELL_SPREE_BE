<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;

class AffiliateMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = auth('affiliate')->userOrFail();
        } catch (JWTException) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if (! $user || ! in_array('affiliate', $user->roles ?? [])) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($user->is_suspended) {
            return response()->json(['message' => 'Account suspended.'], 403);
        }

        return $next($request);
    }
}
