<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Payment;

use App\Exceptions\BusinessException;
use App\Models\PaymentConfig;

class ActivatePaymentProviderAction
{
    public function run(string $provider): void
    {
        $exists = PaymentConfig::where('provider', $provider)->exists();

        if (! $exists) {
            throw BusinessException::notFound('PaymentConfig', $provider);
        }

        PaymentConfig::query()->update(['is_active' => false]);
        PaymentConfig::where('provider', $provider)->update(['is_active' => true]);
    }
}
