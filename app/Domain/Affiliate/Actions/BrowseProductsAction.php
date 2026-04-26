<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\Actions;

use App\Models\Affiliate;
use App\Models\AffiliateLink;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BrowseProductsAction
{
    public function run(Affiliate $affiliate, ?string $type, ?string $search): LengthAwarePaginator
    {
        $products = Product::active()
            ->where('affiliate_enabled', true)
            ->with(['seller', 'productFiles'])
            ->when($type, fn ($q, $t) => $q->where('type', $t))
            ->when($search, fn ($q, $s) => $q->where('title', 'ilike', "%{$s}%"))
            ->latest()
            ->paginate(20);

        $myLinks = AffiliateLink::where('affiliate_id', $affiliate->id)
            ->whereIn('product_id', $products->pluck('id'))
            ->get(['id', 'product_id', 'slug', 'is_active'])
            ->keyBy('product_id');

        $products->each(fn ($p) => $p->existing_link = $myLinks->get($p->id));

        return $products;
    }
}
