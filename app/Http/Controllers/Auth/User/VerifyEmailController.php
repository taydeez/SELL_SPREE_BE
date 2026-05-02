<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    public function __invoke(Request $request): RedirectResponse
    {
        $frontendUrl = config('app.frontend_url', config('app.url'));

        /** @var User|null $user */
        $user = User::find($request->id);

        if (! $user || ! hash_equals(sha1($user->getEmailForVerification()), (string) $request->hash)) {
            return redirect("{$frontendUrl}/auth/verify-email?status=invalid");
        }

        if ($user->hasVerifiedEmail()) {
            return redirect("{$frontendUrl}/auth/verify-email?status=already_verified");
        }

        $user->markEmailAsVerified();

        return redirect("{$frontendUrl}/auth/verify-email?status=verified");
    }
}
