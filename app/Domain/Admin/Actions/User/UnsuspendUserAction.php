<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\User;

use App\Exceptions\BusinessException;
use App\Models\User;
use App\Notifications\Affiliate\AccountUnsuspendedNotification as AffiliateUnsuspended;
use App\Notifications\Seller\AccountUnsuspendedNotification as SellerUnsuspended;

class UnsuspendUserAction
{
    public function run(User $user, ?string $role = null): void
    {
        if (! $user->is_suspended) {
            throw BusinessException::invalidOperation('User is not suspended.');
        }

        $user->update(['is_suspended' => false]);

        match ($role) {
            'seller'    => $user->notify(new SellerUnsuspended),
            'affiliate' => $user->notify(new AffiliateUnsuspended),
            default     => null,
        };
    }
}
