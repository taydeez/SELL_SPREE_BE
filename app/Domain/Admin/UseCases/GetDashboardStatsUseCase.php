<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases;

use App\Models\Affiliate;
use App\Models\Order;
use App\Models\Seller;

class GetDashboardStatsUseCase
{
    public function run(): array
    {
        $totalRevenue      = Order::paid()->sum('amount');
        $platformFees      = Order::paid()->sum('platform_fee');
        $sellerEarnings    = Order::paid()->sum('seller_earnings');
        $affiliateEarnings = Order::paid()->sum('affiliate_earnings');
        $completedOrders   = Order::paid()->count();
        $refundedOrders    = Order::refunded()->count();
        $totalSellers      = Seller::count();
        $approvedSellers   = Seller::approved()->count();
        $totalAffiliates   = Affiliate::count();

        return [
            'revenue' => [
                'total'              => $totalRevenue,
                'platform_fees'      => $platformFees,
                'seller_earnings'    => $sellerEarnings,
                'affiliate_earnings' => $affiliateEarnings,
            ],
            'orders' => [
                'completed' => $completedOrders,
                'refunded'  => $refundedOrders,
            ],
            'sellers' => [
                'total'    => $totalSellers,
                'approved' => $approvedSellers,
                'pending'  => $totalSellers - $approvedSellers,
            ],
            'affiliates' => [
                'total' => $totalAffiliates,
            ],
        ];
    }
}
