<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheResponseMiddleware
{
    /**
     * Number of seconds to cache a response.
     */
    private int $ttl;

    public function __construct()
    {
        $this->ttl = (int) config('cache.response_ttl', env('CACHE_TTL', 300));
    }

    public function handle(Request $request, Closure $next, int $ttl = 0): Response
    {
        // Only cache GET requests
        if (! $request->isMethod('GET')) {
            return $next($request);
        }

        // Skip all auth-related routes
        if ($this->isAuthRoute($request)) {
            return $next($request);
        }

        $cacheKey = $this->buildKey($request);

        if (Cache::has($cacheKey)) {
            /** @var array{status: int, headers: array<string, string>, content: string} $cached */
            $cached = Cache::get($cacheKey);

            return response()
                ->json(
                    json_decode($cached['content'], true),
                    $cached['status'],
                    array_merge($cached['headers'], ['X-Cache' => 'HIT']),
                );
        }

        /** @var Response $response */
        $response = $next($request);

        // Only cache successful responses
        if ($response->isSuccessful()) {
            $effectiveTtl = $ttl > 0 ? $ttl : $this->ttl;

            Cache::put($cacheKey, [
                'status'  => $response->getStatusCode(),
                'headers' => $this->extractHeaders($response),
                'content' => $response->getContent(),
            ], $effectiveTtl);

            $response->headers->set('X-Cache', 'MISS');
        }

        return $response;
    }

    private function isAuthRoute(Request $request): bool
    {
        return str_contains($request->getPathInfo(), '/auth/');
    }

    private function buildKey(Request $request): string
    {
        $url  = $request->fullUrl();
        $user = $request->user()?->id ?? 'guest';

        return 'response:' . md5($user . '|' . $url);
    }

    /**
     * @return array<string, string>
     */
    private function extractHeaders(Response $response): array
    {
        $allowed = ['content-type', 'content-language'];
        $headers = [];

        foreach ($allowed as $header) {
            $value = $response->headers->get($header);
            if ($value !== null) {
                $headers[$header] = $value;
            }
        }

        return $headers;
    }
}
