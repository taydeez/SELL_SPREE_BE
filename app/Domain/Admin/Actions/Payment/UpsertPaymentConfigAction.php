<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Payment;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Models\PaymentConfig;

class UpsertPaymentConfigAction
{
    public function __construct(private readonly CreateAppLogAction $log) {}

    public function run(array $data): PaymentConfig
    {
        $config = PaymentConfig::updateOrCreate(
            ['provider' => $data['provider']],
            [
                'public_key'     => $data['public_key'],
                'secret_key'     => $data['secret_key'],
                'webhook_secret' => $data['webhook_secret'] ?? null,
                'is_active'      => $data['is_active'] ?? false,
            ]
        );

        $this->log->run('info', 'PAYMENT_CONFIG_UPDATED', "Payment config upserted for provider: {$data['provider']}.", ['provider' => $data['provider']]);

        return $config;
    }
}
