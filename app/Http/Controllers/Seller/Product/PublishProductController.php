<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Product;

use App\Domain\Seller\Actions\PublishProductAction;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Middleware\CacheResponseMiddleware;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublishProductController extends Controller
{
    public function __construct(private readonly PublishProductAction $action) {}

    public function __invoke(Request $request, Product $product): JsonResponse
    {
        $seller = Seller::where('user_id', Auth::guard('seller')->id())->firstOrFail();

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        $this->action->run($product);

        $base = rtrim($request->getSchemeAndHttpHost(), '/') . '/api/v1/seller';
        CacheResponseMiddleware::forgetForUser($seller->user_id, "{$base}/products/{$product->id}");
        CacheResponseMiddleware::forgetForUser($seller->user_id, "{$base}/products/{$product->id}/stats");
        CacheResponseMiddleware::forgetForUser($seller->user_id, "{$base}/products");

        return ApiResponse::success(message: 'Product published.');
    }
}
