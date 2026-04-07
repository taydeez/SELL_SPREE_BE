<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Seller;

use App\Domain\Auth\Actions\ResolveSellerProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\Seller\SellerProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CurrentUserController extends Controller
{
    public function __construct(private readonly ResolveSellerProfileAction $action) {}

    public function __invoke(): JsonResponse
    {
        $seller = $this->action->run(Auth::guard('seller')->id());

        return ApiResponse::success(new SellerProfileResource($seller));
    }
}
