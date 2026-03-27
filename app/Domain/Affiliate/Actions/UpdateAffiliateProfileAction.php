<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\Actions;

use App\Models\Affiliate;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class UpdateAffiliateProfileAction
{
    public function run(User $user, Affiliate $affiliate, array $data, ?UploadedFile $avatar = null): Affiliate
    {
        if (isset($data['name'])) {
            $user->update(['name' => $data['name']]);
        }

        $affiliate->update(array_filter([
            'display_name' => $data['display_name'] ?? null,
            'payout_email' => $data['payout_email'] ?? null,
        ], fn ($v) => $v !== null));

        if ($avatar) {
            $affiliate->addMedia($avatar)->toMediaCollection('avatar', 'r2');
        }

        return $affiliate->fresh();
    }
}
