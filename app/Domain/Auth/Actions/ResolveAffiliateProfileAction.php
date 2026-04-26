<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use App\Models\Affiliate;

class ResolveAffiliateProfileAction
{
    public function run(int|string $userId): Affiliate
    {
        return Affiliate::where('user_id', $userId)->with('user')->firstOrFail();
    }
}
