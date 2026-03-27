<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Affiliate;

use App\Const\Auth\AuthMessages;
use App\Domain\Auth\UseCases\LoginUseCase;
use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Affiliate\AffiliateProfileResource;
use App\Http\Resources\ApiResponse;
use App\Models\Affiliate;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __construct(private readonly LoginUseCase $useCase) {}

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $result = $this->useCase->run(
            email: $request->email,
            password: $request->password,
            role: UserRole::Affiliate,
            guard: 'affiliate',
        );

        $affiliate = Affiliate::where('user_id', $result['user']->id)->first();
        $result['user'] = new AffiliateProfileResource($affiliate?->load('user') ?? $result['user']);

        return ApiResponse::success($result, AuthMessages::LOGIN_SUCCESS);
    }
}
