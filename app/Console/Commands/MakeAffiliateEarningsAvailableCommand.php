<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\AffiliateSale;
use Illuminate\Console\Command;

class MakeAffiliateEarningsAvailableCommand extends Command
{
    protected $signature   = 'affiliate:release-earnings';
    protected $description = 'Mark affiliate earnings as available after the refund window has passed';

    public function handle(): void
    {
        $count = AffiliateSale::where('status', 'pending')
            ->where('available_at', '<=', now())
            ->update(['status' => 'available']);

        $this->info("Released {$count} affiliate earning(s) to available status.");
    }
}
