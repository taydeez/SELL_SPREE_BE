<?php
/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */

namespace App\Domain\Payment\Flutterwave\Actions;

use App\Models\Transaction;

class FlutterwaveInitAction
{

    public function run(array $data): Transaction
    {
        try {
            return Transaction::create($data);
        } catch (\Throwable $e) {
            throw new \RuntimeException('Failed to create transaction', 0, $e);
        }
    }
}
