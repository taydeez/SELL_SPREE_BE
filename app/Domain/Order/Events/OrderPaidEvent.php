<?php
/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */


namespace App\Domain\Order\Events;

use App\Models\Transaction;
use App\Models\Order;
use Illuminate\Contracts\Events\ShouldDispatchAfterCommit;

class OrderPaidEvent implements ShouldDispatchAfterCommit
{
    public function __construct(
        public readonly Transaction $transaction
    ) {
    }
}
