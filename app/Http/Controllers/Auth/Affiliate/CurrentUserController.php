<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Affiliate;

use App\Http\Controllers\Controller;
use App\Http\Resources\Affiliate\AffiliateProfileResource;
use App\Http\Resources\ApiResponse;
use App\Models\Affiliate;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CurrentUserController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $user      = Auth::guard('affiliate')->user();
        $affiliate = Affiliate::where('user_id', $user->id)->with('user')->firstOrFail();

        return ApiResponse::success(new AffiliateProfileResource($affiliate));
    }
}
