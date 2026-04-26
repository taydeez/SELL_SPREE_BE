<?php

declare(strict_types=1);

namespace App\Domain\Order\Actions;

use App\Models\AffiliateLink;

class TrackAffiliateLinkAction
{
    public function run(string $slug): array
    {
        $link = AffiliateLink::where('slug', $slug)
            ->where('is_active', true)
            ->with('product:id,slug,title')
            ->firstOrFail();

        $link->increment('click_count');

        return [
            'affiliate_link_id' => $link->id,
            'product_slug'      => $link->product->slug,
        ];
    }
}
