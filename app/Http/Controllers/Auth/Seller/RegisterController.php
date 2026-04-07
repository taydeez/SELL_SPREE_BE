<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Seller;

use App\Const\Auth\AuthMessages;
use App\Domain\Seller\UseCases\RegisterSellerUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\RegisterSellerRequest;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function __construct(private readonly RegisterSellerUseCase $useCase) {}

    public function __invoke(RegisterSellerRequest $request): JsonResponse
    {
        return ApiResponse::created($this->useCase->run($request->validated()), AuthMessages::REGISTER_SUCCESS);
    }
}
