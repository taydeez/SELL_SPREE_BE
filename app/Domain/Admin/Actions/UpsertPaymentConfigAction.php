<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions;

use App\Models\PaymentConfig;

class UpsertPaymentConfigAction
{
    public function run(array $data): PaymentConfig
    {
        return PaymentConfig::updateOrCreate(
            ['provider' => $data['provider']],
            [
                'public_key'     => $data['public_key'],
                'secret_key'     => $data['secret_key'],
                'webhook_secret' => $data['webhook_secret'] ?? null,
                'is_active'      => $data['is_active'] ?? false,
            ]
        );
    }
}
