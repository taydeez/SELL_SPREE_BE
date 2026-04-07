<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Affiliate;

use App\Domain\Auth\Actions\ResolveAffiliateProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Affiliate\AffiliateProfileResource;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CurrentUserController extends Controller
{
    public function __construct(private readonly ResolveAffiliateProfileAction $action) {}

    public function __invoke(): JsonResponse
    {
        $affiliate = $this->action->run(Auth::guard('affiliate')->id());

        return ApiResponse::success(new AffiliateProfileResource($affiliate));
    }
}
