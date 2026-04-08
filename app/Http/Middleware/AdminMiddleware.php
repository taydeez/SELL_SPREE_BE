<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $admin = auth('admin')->userOrFail();
        } catch (JWTException) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if (! $admin) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if (! $admin->is_active) {
            return response()->json(['message' => 'Account disabled.'], 403);
        }

        return $next($request);
    }
}
