<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Admin;

use App\Const\Auth\AuthMessages;
use App\Domain\Auth\UseCases\VerifyResetCodeUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\VerifyResetCodeRequest;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class VerifyResetCodeController extends Controller
{
    public function __construct(private readonly VerifyResetCodeUseCase $useCase) {}

    public function __invoke(VerifyResetCodeRequest $request): JsonResponse
    {
        $this->useCase->run($request->email, 'admin', $request->code);

        return ApiResponse::success(null, AuthMessages::PASSWORD_RESET_VERIFIED);
    }
}
