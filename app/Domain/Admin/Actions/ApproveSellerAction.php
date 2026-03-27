<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions;

use App\Exceptions\BusinessException;
use App\Models\Seller;

class ApproveSellerAction
{
    public function run(Seller $seller): void
    {
        if ($seller->is_approved) {
            throw BusinessException::invalidOperation('Seller is already approved.');
        }

        $seller->update(['is_approved' => true]);
    }
}
