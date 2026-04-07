<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use App\Models\Seller;

class ResolveSellerProfileAction
{
    public function run(int|string $userId): Seller
    {
        return Seller::where('user_id', $userId)->with('user')->firstOrFail();
    }
}
