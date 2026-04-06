<?php
/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */


namespace App\Domain\Payment\Flutterwave\Actions;

use App\Models\Transaction;


class GetFlutterwaveTransactionByRefAction{

    public function run(string $tx_ref): Transaction | null
    {
        $transaction = Transaction::where('tx_ref', $tx_ref)->first();
        return $transaction;
    }


}
