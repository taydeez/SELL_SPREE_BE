<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Product;

use App\Domain\Seller\UseCases\Product\PauseProductUseCase;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CacheResponseMiddleware;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PauseProductController extends Controller
{
    public function __construct(private readonly PauseProductUseCase $useCase) {}

    public function __invoke(Request $request, Product $product): JsonResponse
    {
        $seller = $this->useCase->run(Auth::guard('seller')->id(), $product);

        $base = rtrim($request->getSchemeAndHttpHost(), '/') . '/api/v1/seller';
        CacheResponseMiddleware::forgetForUser($seller->user_id, "{$base}/products/{$product->id}");
        CacheResponseMiddleware::forgetForUser($seller->user_id, "{$base}/products/{$product->id}/stats");
        CacheResponseMiddleware::forgetForUser($seller->user_id, "{$base}/products");

        return ApiResponse::success(message: 'Product paused.');
    }
}
