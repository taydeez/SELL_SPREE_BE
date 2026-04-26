<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions\Product;

use App\Enums\OrderStatus;
use App\Models\Product;

class GetProductStatsAction
{
    public function run(Product $product): array
    {
        $salesRow = $product->orders()
            ->selectRaw(
                'COUNT(*) as total_orders,
                 SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as paid_orders,
                 SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as pending_orders,
                 SUM(CASE WHEN status = ? THEN 1 ELSE 0 END) as refunded_orders,
                 COALESCE(SUM(CASE WHEN status = ? THEN amount          ELSE 0 END), 0) as total_revenue,
                 COALESCE(SUM(CASE WHEN status = ? THEN seller_earnings ELSE 0 END), 0) as seller_earnings',
                [
                    OrderStatus::Paid->value,
                    OrderStatus::Pending->value,
                    OrderStatus::Refunded->value,
                    OrderStatus::Paid->value,
                    OrderStatus::Paid->value,
                ],
            )
            ->first();

        $paidOrders   = (int) $salesRow->paid_orders;
        $totalRevenue = (int) $salesRow->total_revenue;

        $sales = [
            'total_orders'    => (int) $salesRow->total_orders,
            'paid_orders'     => $paidOrders,
            'pending_orders'  => (int) $salesRow->pending_orders,
            'refunded_orders' => (int) $salesRow->refunded_orders,
            'total_revenue'   => $totalRevenue,
            'seller_earnings' => (int) $salesRow->seller_earnings,
            'avg_order_value' => $paidOrders > 0 ? (int) round($totalRevenue / $paidOrders) : 0,
        ];

        $affRow = $product->affiliateLinks()
            ->selectRaw(
                'COUNT(*) as links_count,
                 COALESCE(SUM(click_count), 0) as total_clicks,
                 COALESCE(SUM(sales_count), 0) as total_affiliate_sales,
                 COALESCE(SUM(total_earned), 0) as total_affiliate_earnings',
            )
            ->first();

        $affiliates = [
            'links_count'              => (int) $affRow->links_count,
            'total_clicks'             => (int) $affRow->total_clicks,
            'total_affiliate_sales'    => (int) $affRow->total_affiliate_sales,
            'total_affiliate_earnings' => (int) $affRow->total_affiliate_earnings,
        ];

        $recentOrders = $product->orders()
            ->paid()
            ->latest()
            ->limit(10)
            ->get(['id', 'buyer_email', 'attendee_name', 'variant_id', 'amount', 'seller_earnings', 'payment_provider', 'ticket_number', 'checked_in_at', 'created_at'])
            ->map(fn ($o) => [
                'id'               => $o->id,
                'buyer_email'      => $o->buyer_email,
                'attendee_name'    => $o->attendee_name,
                'variant_id'       => $o->variant_id,
                'ticket_number'    => $o->ticket_number,
                'checked_in_at'    => $o->checked_in_at,
                'amount'           => $o->amount,
                'seller_earnings'  => $o->seller_earnings,
                'payment_provider' => $o->payment_provider,
                'created_at'       => $o->created_at,
            ]);

        $topLinks = $product->affiliateLinks()
            ->with('affiliate:id,display_name,slug')
            ->orderByDesc('sales_count')
            ->orderByDesc('click_count')
            ->limit(5)
            ->get(['id', 'affiliate_id', 'slug', 'click_count', 'sales_count', 'total_earned', 'is_active'])
            ->map(fn ($l) => [
                'id'             => $l->id,
                'slug'           => $l->slug,
                'affiliate_name' => $l->affiliate?->display_name,
                'click_count'    => $l->click_count,
                'sales_count'    => $l->sales_count,
                'total_earned'   => $l->total_earned,
                'is_active'      => $l->is_active,
            ]);

        return compact('sales', 'affiliates', 'recentOrders', 'topLinks');
    }
}
