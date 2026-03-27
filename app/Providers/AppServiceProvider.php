<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->configureRateLimiters();
    }

    private function configureRateLimiters(): void
    {
        // Login: 5 attempts per minute keyed by IP + email
        // Prevents credential-stuffing against a known address from a single IP
        RateLimiter::for('login', function (Request $request): Limit {
            return Limit::perMinute(5)
                ->by(sha1($request->ip() . '|' . mb_strtolower((string) $request->input('email', ''))))
                ->response(fn () => response()->json([
                    'success' => false,
                    'message' => 'Too many login attempts. Please wait a minute and try again.',
                    'errors'  => [],
                ], 429));
        });

        // Registration: 3 attempts per minute per IP
        // Prevents bulk account creation from a single source
        RateLimiter::for('register', function (Request $request): Limit {
            return Limit::perMinute(3)
                ->by($request->ip())
                ->response(fn () => response()->json([
                    'success' => false,
                    'message' => 'Too many registration attempts. Please wait a minute and try again.',
                    'errors'  => [],
                ], 429));
        });
    }
}
