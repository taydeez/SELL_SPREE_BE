<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Seller;

use App\Models\Seller;

class ShowSellerAction
{
    public function run(Seller $seller): Seller
    {
        $seller->load('user');

        return $seller;
    }
}
