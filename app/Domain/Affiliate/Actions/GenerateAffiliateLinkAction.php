<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\Actions;

use App\Enums\ProductStatus;
use App\Exceptions\BusinessException;
use App\Models\Affiliate;
use App\Models\AffiliateLink;
use App\Models\Product;

class GenerateAffiliateLinkAction
{
    public function run(Affiliate $affiliate, Product $product): AffiliateLink
    {
        if ($product->status !== ProductStatus::Active) {
            throw BusinessException::invalidOperation('Links can only be created for active products.');
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
