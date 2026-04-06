<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Account;

use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UpdatePasswordController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $user = Auth::guard('admin')->user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password'         => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if (! Hash::check($request->current_password, $user->password)) {
            throw BusinessException::invalidOperation('Current password is incorrect.');
        }

        $user->update(['password' => Hash::make($request->password)]);

        return ApiResponse::success(['message' => 'Password updated successfully.']);
    }
}
