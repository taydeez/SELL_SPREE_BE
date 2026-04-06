<?php
/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */

namespace App\Domain\Payment\Flutterwave\UseCases;

use App\Domain\Order\Events\OrderPaidEvent;
use App\Domain\Payment\Flutterwave\Actions\GetFlutterwaveTransactionByRefAction;
use App\Domain\Payment\Flutterwave\Actions\UpdateFlutterwaveTransactionAction;
use App\Services\FlutterwaveService;
class FlutterwaveVerifyUseCase{

    public function __construct(
        private readonly FlutterwaveService $flutterwave,
        private readonly GetFlutterwaveTransactionByRefAction $getTransactionByRefAction,
        private readonly UpdateFlutterwaveTransactionAction $updateTransactionAction,
    )
    {
    }

    public function run(array $data): array
    {
        $transaction = null;

        try {
            $verification = $this->flutterwave->verifyTransaction($data['transaction_id']);

            if (($verification['status'] ?? '') !== 'success') {
                throw new \RuntimeException('Failed to verify transaction');
            }

            $verificationData = $verification['data'];
            $transaction      = $this->getTransactionByRefAction->run($data['tx_ref']);

            if (!$transaction) {
                throw new \RuntimeException('Transaction record not found');
            }

            if (
                $verificationData['status'] === 'successful' &&
                $verificationData['amount'] >= $transaction->amount &&
                $verificationData['currency'] === $transaction->currency
            ) {
                $this->updateTransactionAction->run([
                    'transaction_id' => $data['transaction_id'],
                    'status'         => 'successful',
                    'meta'           => $verificationData,
                ], $transaction);

                $transaction->refresh();

                event(new OrderPaidEvent($transaction));

                return [
                    'status'  => 'success',
                    'message' => 'Payment verified successfully.',
                    'data'    => [
                        'tx_ref'         => $transaction->tx_ref,
                        'amount'         => $transaction->amount,
                        'currency'       => $transaction->currency,
                        'transaction_id' => $data['transaction_id'],
                    ],
                ];
            }

            $this->updateTransactionAction->run(['status' => 'failed'], $transaction);

            throw new \RuntimeException('Currency or amount mismatch');

        } catch (\Exception $e) {
            if ($transaction) {
                $this->updateTransactionAction->run(['status' => 'failed'], $transaction);
            }
            throw new \RuntimeException('An error occurred', 0, $e);
        }
    }


}

