<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Affiliate;

use App\Models\Affiliate;

class ShowAffiliateAction
{
    public function run(Affiliate $affiliate): Affiliate
    {
        $affiliate->load('user');

        return $affiliate;
    }
}
