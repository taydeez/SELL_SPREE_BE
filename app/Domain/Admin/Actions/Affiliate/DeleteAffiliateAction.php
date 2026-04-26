<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Affiliate;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Models\Affiliate;

class DeleteAffiliateAction
{
    public function __construct(private readonly CreateAppLogAction $log) {}

    public function run(Affiliate $affiliate): void
    {
        $this->log->run('info', 'AFFILIATE_DELETED', "Affiliate \"{$affiliate->display_name}\" was deleted.", [
            'affiliate_id' => $affiliate->id,
            'email'        => $affiliate->user?->email,
        ]);

        $affiliate->user->delete();
        $affiliate->delete();
    }
}
