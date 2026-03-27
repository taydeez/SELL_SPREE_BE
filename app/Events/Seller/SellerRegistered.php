<?php

declare(strict_types=1);

namespace App\Events\Seller;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SellerRegistered
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public readonly User $user,
        public readonly Seller $seller,
    ) {}
}
