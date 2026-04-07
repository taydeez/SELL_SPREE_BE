<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Settings;

use App\Domain\Seller\UseCases\Settings\UpdateProfileUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\UpdateSellerProfileRequest;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UpdateProfileController extends Controller
{
    public function __construct(private readonly UpdateProfileUseCase $useCase) {}

    public function __invoke(UpdateSellerProfileRequest $request): JsonResponse
    {
        return ApiResponse::success($this->useCase->run(
            Auth::guard('seller')->user(),
            $request->validated(),
            $request->file('avatar'),
        ));
    }
}
