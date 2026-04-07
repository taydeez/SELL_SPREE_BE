<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases\Product;

use App\Domain\Admin\Actions\Product\ShowProductAction;
use App\Http\Resources\Admin\ProductDetailResource;
use App\Models\Product;

class ShowProductUseCase
{
    public function __construct(private readonly ShowProductAction $action) {}

    public function run(Product $product): ProductDetailResource
    {
        $product = $this->action->run($product);

        if ($product->isTicket()) {
            $product->load(['event', 'variants']);
        }

        return new ProductDetailResource($product);
    }
}
