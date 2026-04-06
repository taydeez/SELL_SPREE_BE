<?php
/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */

declare(strict_types=1);

namespace App\Domain\Payment\Flutterwave\Actions;

use App\Models\Transaction;
use App\Services\FlutterwaveService;

class FlutterwaveVerifyAction
{
    public function __construct(private readonly FlutterwaveService $flutterwaveService) {}

    public function run(string $transactionId): array
    {
        return $this->flutterwaveService->verifyTransaction($transactionId);
    }
}
