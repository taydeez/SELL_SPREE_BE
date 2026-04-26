<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Seller;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Exceptions\BusinessException;
use App\Models\Seller;
use App\Notifications\Seller\SellerApprovedNotification;

class ApproveSellerAction
{
    public function __construct(private readonly CreateAppLogAction $log) {}

    public function run(Seller $seller): void
    {
        if ($seller->is_approved) {
            throw BusinessException::invalidOperation('Seller is already approved.');
        }

        $seller->update(['is_approved' => true]);

        $seller->user->notify(new SellerApprovedNotification($seller));

        $this->log->run('info', 'SELLER_APPROVED', "Seller \"{$seller->store_name}\" was approved.", [
            'seller_id' => $seller->id,
        ]);
    }
}
