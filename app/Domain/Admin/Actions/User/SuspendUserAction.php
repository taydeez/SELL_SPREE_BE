<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\User;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Exceptions\BusinessException;
use App\Models\User;
use App\Notifications\Affiliate\AccountSuspendedNotification as AffiliateSuspended;
use App\Notifications\Seller\AccountSuspendedNotification as SellerSuspended;

class SuspendUserAction
{
    public function __construct(private readonly CreateAppLogAction $log) {}

    public function run(User $user, ?string $role = null): void
    {
        if ($user->is_suspended) {
            throw BusinessException::invalidOperation('User is already suspended.');
        }

        $user->update(['is_suspended' => true]);

        match ($role) {
            'seller'    => $user->notify(new SellerSuspended),
            'affiliate' => $user->notify(new AffiliateSuspended),
            default     => null,
        };

        $this->log->run('warning', 'USER_SUSPENDED', "User \"{$user->email}\" was suspended.", [
            'user_id' => $user->id,
            'email'   => $user->email,
            'role'    => $role,
        ]);
    }
}
