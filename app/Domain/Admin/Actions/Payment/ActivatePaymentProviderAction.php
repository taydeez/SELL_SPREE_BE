<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Payment;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Exceptions\BusinessException;
use App\Models\PaymentConfig;

class ActivatePaymentProviderAction
{
    public function __construct(private readonly CreateAppLogAction $log) {}

    public function run(string $provider): void
    {
        $exists = PaymentConfig::where('provider', $provider)->exists();

        if (! $exists) {
            throw BusinessException::notFound('PaymentConfig', $provider);
        }

        PaymentConfig::query()->update(['is_active' => false]);
        PaymentConfig::where('provider', $provider)->update(['is_active' => true]);

        $this->log->run('info', 'PAYMENT_PROVIDER_ACTIVATED', "Payment provider activated: {$provider}.", ['provider' => $provider]);
    }
}
