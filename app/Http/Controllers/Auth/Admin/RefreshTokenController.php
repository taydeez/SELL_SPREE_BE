<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Admin;

use App\Const\Auth\AuthMessages;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RefreshTokenController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $token = Auth::guard('admin')->refresh();

        return ApiResponse::success([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::guard('admin')->factory()->getTTL() * 60,
        ], AuthMessages::TOKEN_REFRESHED);
    }
}
