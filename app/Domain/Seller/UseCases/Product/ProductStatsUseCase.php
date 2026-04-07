<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Product;

use App\Domain\Seller\Actions\Product\GetProductStatsAction;
use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Exceptions\BusinessException;
use App\Http\Resources\Seller\ProductResource;
use App\Models\Product;

class ProductStatsUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly GetProductStatsAction $getStats,
    ) {}

    public function run(int|string $userId, Product $product): array
    {
        $seller = $this->resolveSeller->run($userId);

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        $stats = $this->getStats->run($product);

        $ticketStats = null;

        if ($product->isTicket()) {
            $product->load(['variants', 'event']);

            $checkedInCount = $product->orders()->paid()->whereNotNull('checked_in_at')->count();

            $tierStats = $product->variants->map(fn ($v) => [
                'id'           => $v->id,
                'name'         => $v->name,
                'price'        => $v->price,
                'stock'        => $v->stock,
                'is_active'    => $v->is_active,
                'tickets_sold' => $product->orders()->paid()->where('variant_id', $v->id)->count(),
            ]);

            $ticketStats = [
                'checked_in_count' => $checkedInCount,
                'tier_stats'       => $tierStats,
            ];
        }

        $product->load(['productFiles', 'tags']);

        return [
            'product'       => new ProductResource($product),
            'sales'         => $stats['sales'],
            'affiliates'    => $stats['affiliates'],
            'recent_orders' => $stats['recentOrders'],
            'top_links'     => $stats['topLinks'],
            'ticket_stats'  => $ticketStats,
        ];
    }
}
