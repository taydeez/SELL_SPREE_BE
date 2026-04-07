<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Product;

use App\Domain\Admin\Actions\Product\SuspendProductAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class SuspendProductController extends Controller
{
    public function __construct(private readonly SuspendProductAction $action) {}

    public function __invoke(Product $product): JsonResponse
    {
        $this->action->run($product);

        return ApiResponse::success(message: 'Product suspended.');
    }
}
