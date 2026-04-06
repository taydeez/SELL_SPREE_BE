<?php
/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */

namespace App\Domain\Payment\Flutterwave\Actions;
use App\Models\Transaction;

class UpdateFlutterwaveTransactionAction{

    public function run(array $data, Transaction $transaction): void
    {
        $transaction->update($data);
    }


}
