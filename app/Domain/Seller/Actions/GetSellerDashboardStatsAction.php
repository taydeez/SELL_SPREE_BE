<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Enums\OrderStatus;
use App\Models\Order;
use App\Models\Seller;

class GetSellerDashboardStatsAction
{
    public function run(Seller $seller): array
    {
        $paidOrders = Order::where('seller_id', $seller->id)
            ->where('status', OrderStatus::Paid)
            ->selectRaw('COUNT(*) as count, SUM(seller_earnings) as earnings, SUM(affiliate_earnings) as affiliate_total')
            ->first();

        $productCount  = $seller->products()->count();
        $activeCount   = $seller->products()->active()->count();
        $totalViews    = $seller->products()->sum('view_count');

        $affiliateLinks = \App\Models\AffiliateLink::whereIn(
            'product_id',
            $seller->products()->pluck('id')
        );

        return [
            'total_revenue'         => (int) ($paidOrders->earnings ?? 0),
            'affiliate_earnings'    => (int) ($paidOrders->affiliate_total ?? 0),
            'total_sales'           => (int) ($paidOrders->count ?? 0),
            'total_products'        => $productCount,
            'active_products'       => $activeCount,
            'total_views'           => (int) $totalViews,
            'total_affiliate_links' => $affiliateLinks->count(),
        ];
    }
}
