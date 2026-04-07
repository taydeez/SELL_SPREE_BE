<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Models\Seller;

class ResolveCurrentSellerAction
{
    public function run(int|string $userId): Seller
    {
        return Seller::where('user_id', $userId)->firstOrFail();
    }
}
