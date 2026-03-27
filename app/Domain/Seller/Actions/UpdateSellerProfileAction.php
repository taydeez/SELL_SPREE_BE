<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class UpdateSellerProfileAction
{
    public function run(User $user, Seller $seller, array $data, ?UploadedFile $avatar = null): Seller
    {
        if (isset($data['name'])) {
            $user->update(['name' => $data['name']]);
        }

        $seller->update(array_filter([
            'store_name'   => $data['store_name'] ?? null,
            'bio'          => $data['bio'] ?? null,
            'payout_email' => $data['payout_email'] ?? null,
        ], fn ($v) => $v !== null));

        if ($avatar) {
            $seller->addMedia($avatar)->toMediaCollection('avatar', 'r2');
        }

        return $seller->fresh();
    }
}
