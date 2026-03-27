<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\Actions;

use App\Models\Affiliate;
use App\Models\User;

class DeleteAffiliateAccountAction
{
    public function run(User $user, Affiliate $affiliate): void
    {
        $affiliate->delete();
        $user->delete();
    }
}
