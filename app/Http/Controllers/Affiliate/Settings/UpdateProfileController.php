<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Settings;

use App\Domain\Affiliate\UseCases\Settings\UpdateProfileUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Affiliate\UpdateAffiliateProfileRequest;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UpdateProfileController extends Controller
{
    public function __construct(private readonly UpdateProfileUseCase $useCase) {}

    public function __invoke(UpdateAffiliateProfileRequest $request): JsonResponse
    {
        return ApiResponse::success($this->useCase->run(
            Auth::guard('affiliate')->user(),
            $request->validated(),
            $request->file('avatar'),
        ));
    }
}
