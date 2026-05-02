<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->configureEmailVerification();
        $this->configureSuperAdminGate();
        $this->configureRateLimiters();
    }

    private function configureEmailVerification(): void
    {
        VerifyEmail::createUrlUsing(function ($notifiable) {
            return URL::temporarySignedRoute(
                'auth.verify-email',
                now()->addMinutes(60),
                [
                    'id'   => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );
        });
    }

    private function configureSuperAdminGate(): void
    {
        Gate::before(function ($user, string $ability) {
            if (method_exists($user, 'hasRole') && $user->hasRole('super-admin')) {
                return true;
            }
        });
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
