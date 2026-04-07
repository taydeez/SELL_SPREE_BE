<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Product;

use App\Domain\Seller\UseCases\Product\CreateProductUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\CreateProductRequest;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CreateProductController extends Controller
{
    public function __construct(private readonly CreateProductUseCase $useCase) {}

    public function __invoke(CreateProductRequest $request): JsonResponse
    {
        return ApiResponse::created($this->useCase->run(Auth::guard('seller')->id(), $request->validated()));
    }
}
