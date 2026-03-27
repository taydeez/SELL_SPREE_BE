<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Models\Seller;
use App\Models\User;

class DeleteSellerAccountAction
{
    public function run(User $user, Seller $seller): void
    {
        $seller->delete();
        $user->delete();
    }
}
