<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Account;

use App\Domain\Admin\UseCases\Account\UpdatePasswordUsecase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdatePasswordRequest;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;


class UpdatePasswordController extends Controller
{
    public function __construct(private UpdatePasswordUsecase $useCase){}

    public function __invoke(UpdatePasswordRequest $request): JsonResponse
    {
        $this->useCase->run(data: $request->validated());
        return ApiResponse::success(['message' => 'Password updated successfully.']);
    }
}
