<?php
/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */

namespace App\Domain\Payment\Flutterwave\Actions;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Models\Transaction;

class FlutterwaveInitAction
{
    public function __construct(private readonly CreateAppLogAction $log) {}

    public function run(array $data): Transaction
    {
        try {
            return Transaction::create($data);
        } catch (\Throwable $e) {
            $this->log->run('error', 'PAYMENT_INIT_FAILED', $e->getMessage(), [
                'tx_ref'    => $data['tx_ref'] ?? null,
                'amount'    => $data['amount'] ?? null,
                'exception' => get_class($e),
            ]);
            throw new \RuntimeException('Failed to create transaction', 0, $e);
        }
    }
}
