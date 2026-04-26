<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Admin;

use App\Const\Auth\AuthMessages;
use App\Domain\Auth\UseCases\ResetPasswordUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class ResetPasswordController extends Controller
{
    public function __construct(private readonly ResetPasswordUseCase $useCase) {}

    public function __invoke(ResetPasswordRequest $request): JsonResponse
    {
        $this->useCase->run($request->email, 'admin', $request->code, $request->password);

        return ApiResponse::success(null, AuthMessages::PASSWORD_RESET_CONFIRMED);
    }
}
