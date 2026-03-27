<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Product;

use App\Domain\Seller\Actions\CreateProductAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\CreateProductRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\Seller\ProductResource;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CreateProductController extends Controller
{
    public function __construct(private readonly CreateProductAction $action) {}

    public function __invoke(CreateProductRequest $request): JsonResponse
    {
        $seller  = Seller::where('user_id', Auth::guard('seller')->id())->firstOrFail();
        $product = $this->action->run($seller, $request->validated());

        return ApiResponse::created(new ProductResource($product));
    }
}
