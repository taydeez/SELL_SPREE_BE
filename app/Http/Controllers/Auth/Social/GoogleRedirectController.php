<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Social;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class GoogleRedirectController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $portal = $request->route('portal', 'customer');

        $url = Socialite::driver('google')
            ->stateless()
            ->with(['state' => $portal])
            ->redirect()
            ->getTargetUrl();

        return response()->json(['redirect_url' => $url]);
    }
}
