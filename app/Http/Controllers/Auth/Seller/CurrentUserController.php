<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\Seller\SellerProfileResource;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CurrentUserController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $user   = Auth::guard('seller')->user();
        $seller = Seller::where('user_id', $user->id)->with('user')->firstOrFail();

        return ApiResponse::success(new SellerProfileResource($seller));
    }
}
