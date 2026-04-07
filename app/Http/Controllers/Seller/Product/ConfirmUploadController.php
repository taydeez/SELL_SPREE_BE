<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Product;

use App\Domain\Seller\UseCases\Product\ConfirmUploadUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\ConfirmUploadRequest;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ConfirmUploadController extends Controller
{
    public function __construct(private readonly ConfirmUploadUseCase $useCase) {}

    public function __invoke(ConfirmUploadRequest $request, Product $product): JsonResponse
    {
        return ApiResponse::success($this->useCase->run(Auth::guard('seller')->id(), $product, $request->validated()));
    }
}
