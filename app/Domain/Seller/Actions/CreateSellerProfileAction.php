<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Models\Seller;
use App\Models\User;

class CreateSellerProfileAction
{
    public function run(User $user, array $data): Seller
    {
        return Seller::create([
            'user_id'         => $user->id,
            'store_name'      => $data['store_name'],
            'bio'             => $data['bio'] ?? null,
            'payout_email'    => $data['payout_email'] ?? $user->email,
            'commission_rate' => 0,
            'is_approved'     => false,
        ]);
    }
}
