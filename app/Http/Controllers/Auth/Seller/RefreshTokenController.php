<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RefreshTokenController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $token = Auth::guard('seller')->refresh();

        return ApiResponse::success([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::guard('seller')->factory()->getTTL() * 60,
        ]);
    }
}
