<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Affiliate;

use App\Const\Auth\AuthMessages;
use App\Domain\Affiliate\UseCases\RegisterAffiliateUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Affiliate\RegisterAffiliateRequest;
use App\Http\Resources\Affiliate\AffiliateProfileResource;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __construct(private readonly RegisterAffiliateUseCase $useCase) {}

    public function __invoke(RegisterAffiliateRequest $request): JsonResponse
    {
        $result = $this->useCase->run($request->validated());

        return ApiResponse::created(
            new AffiliateProfileResource($result['affiliate']->load('user')),
            AuthMessages::REGISTER_SUCCESS,
        );
    }
}
