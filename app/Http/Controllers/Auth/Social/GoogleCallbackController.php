<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Social;

use App\Domain\Auth\UseCases\SocialLoginUseCase;
use App\Enums\UserRole;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleCallbackController extends Controller
{
    public function __construct(private readonly SocialLoginUseCase $useCase) {}

    public function __invoke(Request $request): RedirectResponse
    {
        $portal    = $request->query('state', 'customer');
        $frontendUrl = rtrim(config('app.frontend_url'), '/');

        try {
            $role       = UserRole::from($portal);
            $socialUser = Socialite::driver('google')->stateless()->user();
            $result     = $this->useCase->run($socialUser, $role);

            $token  = $result['access_token'];
            $portal = $result['portal'];

            return redirect("{$frontendUrl}/auth/social/callback?token={$token}&portal={$portal}");
        } catch (\Throwable $e) {
            $message = urlencode($e instanceof BusinessException ? $e->getMessage() : 'Google authentication failed.');

            return redirect("{$frontendUrl}/auth/social/callback?error={$message}");
        }
    }
}
