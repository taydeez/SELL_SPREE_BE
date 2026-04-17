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
use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Services\FlutterwaveService;
class FlutterwaveVerifyUseCase{

    public function __construct(
        private readonly FlutterwaveService $flutterwave,
        private readonly GetFlutterwaveTransactionByRefAction $getTransactionByRefAction,
        private readonly UpdateFlutterwaveTransactionAction $updateTransactionAction,
        private readonly CreateAppLogAction $log,
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
            $transaction = $this->getTransactionByRefAction->run($data['tx_ref']);

            if (!$transaction) {
                $this->log->run('warning', 'PAYMENT_TX_NOT_FOUND', 'Transaction record not found during verification.', [
                    'tx_ref'         => $data['tx_ref'] ?? null,
                    'transaction_id' => $data['transaction_id'] ?? null,
                ]);
                throw new \RuntimeException('Transaction record not found');
            }

            // Idempotency: already processed — return success without re-firing event
            if ($transaction->status === 'successful') {
                return [
                    'status'  => 'success',
                    'message' => 'Payment already verified.',
                    'data'    => [
                        'tx_ref'         => $transaction->tx_ref,
                        'amount'         => $transaction->amount,
                        'currency'       => $transaction->currency,
                        'transaction_id' => $data['transaction_id'],
                    ],
                ];
            }

            // Flutterwave returns amount in naira; transaction stores cents
            $paidNaira     = (float) ($verificationData['amount'] ?? 0);
            $expectedNaira = $transaction->amount / 100;

            if (
                $verificationData['status'] === 'successful' &&
                $paidNaira >= $expectedNaira &&
                $verificationData['currency'] === $transaction->currency
            ) {
                $this->updateTransactionAction->run([
                    'transaction_id' => $data['transaction_id'],
                    'status'         => 'successful',
                    'meta'           => $verificationData,
                ], $transaction);

                $transaction->refresh();

                $this->log->run('info', 'PAYMENT_VERIFIED', 'Flutterwave payment verified successfully.', [
                    'tx_ref'         => $transaction->tx_ref,
                    'transaction_id' => $data['transaction_id'],
                    'amount'         => $transaction->amount,
                ]);

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

            $this->log->run('warning', 'PAYMENT_AMOUNT_MISMATCH', 'Payment verification failed: amount or currency mismatch.', [
                'tx_ref'            => $transaction->tx_ref,
                'transaction_id'    => $data['transaction_id'] ?? null,
                'expected_naira'    => $expectedNaira,
                'received_naira'    => $paidNaira,
                'expected_currency' => $transaction->currency,
                'received_currency' => $verificationData['currency'] ?? null,
            ]);

            throw new \RuntimeException('Currency or amount mismatch');

        } catch (\Exception $e) {
            $this->log->run('error', 'PAYMENT_VERIFY_FAILED', $e->getMessage(), [
                'tx_ref'         => $data['tx_ref'] ?? null,
                'transaction_id' => $data['transaction_id'] ?? null,
                'exception'      => get_class($e),
            ]);
            if ($transaction) {
                $this->updateTransactionAction->run(['status' => 'failed'], $transaction);
            }
            throw new \RuntimeException('An error occurred', 0, $e);
        }
    }


}

