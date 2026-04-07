<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Affiliate;

use App\Const\Auth\AuthMessages;
use App\Domain\Auth\UseCases\AffiliateLoginUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __construct(private readonly AffiliateLoginUseCase $useCase) {}

    public function __invoke(LoginRequest $request): JsonResponse
    {
        return ApiResponse::success($this->useCase->run($request->email, $request->password), AuthMessages::LOGIN_SUCCESS);
    }
}
