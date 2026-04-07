<?php

declare(strict_types=1);

namespace App\Domain\Order\UseCases;

use App\Domain\Order\Actions\CreateOrderAction;
use App\Exceptions\BusinessException;
use App\Http\Resources\Public\OrderResource;
use App\Models\AffiliateLink;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;

class CreateOrderUseCase
{
    public function __construct(
        private readonly CreateOrderAction $createOrderAction,
    ) {}

    public function run(array $data): OrderResource
    {
        return DB::transaction(function () use ($data) {
            $product = Product::where('slug', $data['product_slug'])->firstOrFail();

            $affiliateLink     = null;
            $affiliateEarnings = 0;

            if (!empty($data['affiliate_link_id'])) {
                $affiliateLink = AffiliateLink::find($data['affiliate_link_id']);

                if ($affiliateLink) {
                    $commissionRate    = $affiliateLink->affiliate->commission_rate ?? 0;
                    $affiliateEarnings = (int) round($product->price * $commissionRate / 100);
                }
            }

            $price        = $product->price;
            $variantId    = null;
            $attendeeName = null;

            if ($product->isTicket()) {
                $variantId    = $data['variant_id'];
                $attendeeName = $data['attendee_name'];

                $variant = ProductVariant::where('id', $variantId)
                    ->where('product_id', $product->id)
                    ->where('is_active', true)
                    ->firstOrFail();

                if ($variant->stock !== null && $variant->stock <= 0) {
                    throw BusinessException::invalidOperation('Tier is sold out');
                }

                $price = $variant->price;

                if ($variant->stock !== null) {
                    $variant->decrement('stock');
                }

                if ($affiliateLink) {
                    $commissionRate    = $affiliateLink->affiliate->commission_rate ?? 0;
                    $affiliateEarnings = (int) round($price * $commissionRate / 100);
                }
            }

            $platformFeePercent = config('payment.platform_fee_percent', 10);
            $platformFee        = (int) round($price * $platformFeePercent / 100);
            $sellerEarnings     = $price - $platformFee - $affiliateEarnings;

            $order = $this->createOrderAction->run([
                'buyer_email'        => $data['buyer_email'],
                'product_id'         => $product->id,
                'variant_id'         => $variantId,
                'seller_id'          => $product->seller_id,
                'affiliate_link_id'  => $affiliateLink?->id,
                'attendee_name'      => $attendeeName,
                'amount'             => $price,
                'platform_fee'       => $platformFee,
                'seller_earnings'    => $sellerEarnings,
                'affiliate_earnings' => $affiliateEarnings,
            ]);

            return new OrderResource($order);
        });
    }
}
