<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Seller;

use App\Const\Auth\AuthMessages;
use App\Domain\Seller\UseCases\RegisterSellerUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\RegisterSellerRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\Seller\SellerProfileResource;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __construct(private readonly RegisterSellerUseCase $useCase) {}

    public function __invoke(RegisterSellerRequest $request): JsonResponse
    {
        $result = $this->useCase->run($request->validated());

        return ApiResponse::created(
            new SellerProfileResource($result['seller']->load('user')),
            AuthMessages::REGISTER_SUCCESS,
        );
    }
}
