<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\Actions;

use App\Models\Affiliate;

class ResolveCurrentAffiliateAction
{
    public function run(int|string $userId): Affiliate
    {
        return Affiliate::where('user_id', $userId)->firstOrFail();
    }
}
