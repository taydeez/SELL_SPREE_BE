<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Models\Seller;
use App\Models\User;
use Illuminate\Support\Str;

class CreateSellerProfileAction
{
    public function run(User $user, array $data): Seller
    {
        return Seller::firstOrCreate(
            ['user_id' => $user->id],
            [
                'store_name'      => $data['store_name'] ?? null,
                'store_slug'      => $this->generateTempSlug($user->name),
                'bio'             => $data['bio'] ?? null,
                'payout_email'    => $data['payout_email'] ?? $user->email,
                'commission_rate' => 0,
                'is_approved'     => false,
            ]
        );
    }

    private function generateTempSlug(string $name): string
    {
        $base  = Str::slug($name) ?: Str::lower(Str::random(8));
        $slug  = $base;
        $count = 1;

        while (Seller::where('store_slug', $slug)->exists()) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }
}
