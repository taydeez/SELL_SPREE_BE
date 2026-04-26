<?php

declare(strict_types=1);

namespace App\Domain\Order\Actions;

use App\Enums\OrderStatus;
use App\Exceptions\BusinessException;
use App\Models\Order;
use App\Models\Product;

class CheckExistingOrderAction
{
    public function run(string $email, string $productSlug): bool
    {
        $product = Product::where('slug', $productSlug)->first();

        if (!$product) {
            throw BusinessException::notFound('Product');
        }

        return Order::where('product_id', $product->id)
            ->where('buyer_email', $email)
            ->where('status', OrderStatus::Paid)
            ->exists();
    }
}
