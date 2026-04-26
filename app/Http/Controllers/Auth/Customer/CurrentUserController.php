<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Customer;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CurrentUserController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return ApiResponse::success(Auth::guard('customer')->user());
    }
}
