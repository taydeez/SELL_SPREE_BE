<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Account;

use App\Domain\Admin\UseCases\Account\UpdateProfileUsecase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProfileRequest;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;


class UpdateProfileController extends Controller
{

    public function __construct(private UpdateProfileUsecase $useCase) {}

    public function __invoke(UpdateProfileRequest $request): JsonResponse
    {

        $userData = $this->useCase->run(data: $request->validated());

        return ApiResponse::success($userData);
    }
}
