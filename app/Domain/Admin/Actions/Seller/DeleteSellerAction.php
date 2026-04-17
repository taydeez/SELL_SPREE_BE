<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Seller;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Models\Seller;

class DeleteSellerAction
{
    public function __construct(private readonly CreateAppLogAction $log) {}

    public function run(Seller $seller): void
    {
        $this->log->run('info', 'SELLER_DELETED', "Seller \"{$seller->store_name}\" was deleted.", [
            'seller_id' => $seller->id,
            'email'     => $seller->user?->email,
        ]);

        $seller->user->delete();
        $seller->delete();
    }
}
