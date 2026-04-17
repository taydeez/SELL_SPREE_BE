<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\Actions;

use App\Models\Affiliate;
use App\Models\User;

class CreateAffiliateProfileAction
{
    public function run(User $user, array $data): Affiliate
    {
        return Affiliate::create([
            'user_id'         => $user->id,
            'display_name' => $data['display_name'] ?? $user->name,
            'payout_email' => $data['payout_email'] ?? $user->email,
        ]);
    }
}
