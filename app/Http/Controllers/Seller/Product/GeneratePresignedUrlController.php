<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Product;

use App\Domain\Seller\Actions\GeneratePresignedUploadUrlAction;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\GeneratePresignedUrlRequest;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GeneratePresignedUrlController extends Controller
{
    public function __construct(private readonly GeneratePresignedUploadUrlAction $action) {}

    public function __invoke(GeneratePresignedUrlRequest $request, Product $product): JsonResponse
    {
        $seller = Seller::where('user_id', Auth::guard('seller')->id())->firstOrFail();

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        $result = $this->action->run($product, $request->validated());

        return ApiResponse::success($result);
    }
}
