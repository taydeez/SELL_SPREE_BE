<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Payment;

use App\Models\PaymentConfig;
use Illuminate\Database\Eloquent\Collection;

class ListPaymentConfigsAction
{
    public function run(): Collection
    {
        return PaymentConfig::all();
    }
}
