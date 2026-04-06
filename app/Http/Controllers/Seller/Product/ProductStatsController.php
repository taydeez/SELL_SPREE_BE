<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Product;

use App\Enums\OrderStatus;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\Seller\ProductResource;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProductStatsController extends Controller
{
    public function __invoke(Product $product): JsonResponse
    {
        $seller = Seller::where('user_id', Auth::guard('seller')->id())->firstOrFail();

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        // ─── Sales stats (single query) ───────────────────────────────────────
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

        $paidOrders    = (int) $salesRow->paid_orders;
        $totalRevenue  = (int) $salesRow->total_revenue;

        $sales = [
            'total_orders'    => (int) $salesRow->total_orders,
            'paid_orders'     => $paidOrders,
            'pending_orders'  => (int) $salesRow->pending_orders,
            'refunded_orders' => (int) $salesRow->refunded_orders,
            'total_revenue'   => $totalRevenue,
            'seller_earnings' => (int) $salesRow->seller_earnings,
            'avg_order_value' => $paidOrders > 0 ? (int) round($totalRevenue / $paidOrders) : 0,
        ];

        // ─── Affiliate stats (single query) ───────────────────────────────────
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

        // ─── Recent paid orders ───────────────────────────────────────────────
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

        // ─── Top affiliate links ──────────────────────────────────────────────
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

        // ─── Ticket-specific stats ────────────────────────────────────────────
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
                'tickets_sold' => $product->orders()
                    ->paid()
                    ->where('variant_id', $v->id)
                    ->count(),
            ]);

            $ticketStats = [
                'checked_in_count' => $checkedInCount,
                'tier_stats'       => $tierStats,
            ];
        }

        $product->load(['productFiles', 'tags']);

        return ApiResponse::success([
            'product'       => new ProductResource($product),
            'sales'         => $sales,
            'affiliates'    => $affiliates,
            'recent_orders' => $recentOrders,
            'top_links'     => $topLinks,
            'ticket_stats'  => $ticketStats,
        ]);
    }
}
