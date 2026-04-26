<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\Actions;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Enums\ProductStatus;
use App\Exceptions\BusinessException;
use App\Models\Affiliate;
use App\Models\AffiliateLink;
use App\Models\Product;

class GenerateAffiliateLinkAction
{
    public function __construct(private readonly CreateAppLogAction $log) {}

    public function run(Affiliate $affiliate, Product $product): AffiliateLink
    {
        if ($product->seller?->user_id === $affiliate->user_id) {
            throw BusinessException::invalidOperation('You cannot create an affiliate link for your own product.');
        }

        if ($product->status !== ProductStatus::Active) {
            $this->log->run('warning', 'AFFILIATE_LINK_BLOCKED', 'Link generation blocked: product not active.', [
                'product_id'   => $product->id,
                'affiliate_id' => $affiliate->id,
                'status'       => $product->status?->value,
            ], $affiliate->user);
            throw BusinessException::invalidOperation('Links can only be created for active products.');
        }

        if (! $product->affiliate_enabled) {
            $this->log->run('warning', 'AFFILIATE_LINK_BLOCKED', 'Link generation blocked: affiliate marketing disabled for product.', [
                'product_id'   => $product->id,
                'affiliate_id' => $affiliate->id,
            ], $affiliate->user);
            throw BusinessException::invalidOperation('This product is not available for affiliate marketing.');
        }

        // Return existing active link if one already exists for this affiliate+product
        $existing = AffiliateLink::where('affiliate_id', $affiliate->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existing) {
            return $existing;
        }

        return AffiliateLink::create([
            'affiliate_id' => $affiliate->id,
            'product_id'   => $product->id,
            'is_active'    => true,
        ]);
    }
}
