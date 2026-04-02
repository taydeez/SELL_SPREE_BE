<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Product;

use App\Domain\Seller\Actions\ConfirmFileUploadAction;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\ConfirmUploadRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\Seller\ProductFileResource;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ConfirmUploadController extends Controller
{
    public function __construct(private readonly ConfirmFileUploadAction $action) {}

    public function __invoke(ConfirmUploadRequest $request, Product $product): JsonResponse
    {
        $seller = Seller::where('user_id', Auth::guard('seller')->id())->firstOrFail();

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        $file = $this->action->run($product, $request->validated());

        return ApiResponse::success(new ProductFileResource($file));
    }
}
