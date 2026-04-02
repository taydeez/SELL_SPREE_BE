<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Product;

use App\Domain\Seller\Actions\UpdateProductAction;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\UpdateProductRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\Seller\ProductResource;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UpdateProductController extends Controller
{
    public function __construct(private readonly UpdateProductAction $action) {}

    public function __invoke(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $seller = Seller::where('user_id', Auth::guard('seller')->id())->firstOrFail();

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        $updated = $this->action->run($product, $request->validated());

        return ApiResponse::success(new ProductResource($updated->load(['variants', 'tags'])));
    }
}
