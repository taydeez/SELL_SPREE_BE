<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if (! $user || $user->role !== UserRole::Admin) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if ($user->is_suspended) {
            return response()->json(['message' => 'Account suspended.'], 403);
        }

        return $next($request);
    }
}
