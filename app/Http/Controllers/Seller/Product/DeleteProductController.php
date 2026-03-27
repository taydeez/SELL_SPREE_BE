<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Product;

use App\Domain\Seller\Actions\DeleteProductAction;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DeleteProductController extends Controller
{
    public function __construct(private readonly DeleteProductAction $action) {}

    public function __invoke(Product $product): JsonResponse
    {
        $seller = Seller::where('user_id', Auth::guard('seller')->id())->firstOrFail();

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        $this->action->run($product);

        return ApiResponse::noContent();
    }
}
