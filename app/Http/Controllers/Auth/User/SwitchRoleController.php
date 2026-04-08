<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\User;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SwitchRoleController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $request->validate(['role' => 'required|in:seller,affiliate,customer']);

        $user = auth('seller')->user() ?? auth('affiliate')->user() ?? auth('customer')->user();

        if (! $user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $targetRole = $request->role;

        if (! $user->hasRole($targetRole)) {
            throw BusinessException::forbidden();
        }

        $user->update(['active_role' => $targetRole]);

        $token = Auth::guard($targetRole)->login($user);

        return ApiResponse::success([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => Auth::guard($targetRole)->factory()->getTTL() * 60,
            'active_role'  => $targetRole,
        ], 'Role switched.');
    }
}
