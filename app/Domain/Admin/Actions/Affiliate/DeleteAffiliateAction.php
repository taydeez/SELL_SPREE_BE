<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Affiliate;

use App\Models\Affiliate;

class DeleteAffiliateAction
{
    public function run(Affiliate $affiliate): void
    {
        $affiliate->user->delete();
        $affiliate->delete();
    }
}
