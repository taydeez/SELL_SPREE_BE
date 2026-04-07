<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Seller;

use App\Models\Seller;

class DeleteSellerAction
{
    public function run(Seller $seller): void
    {
        $seller->user->delete();
        $seller->delete();
    }
}
