<?php
/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */

namespace App\Domain\Payment\Flutterwave\UseCases;

use App\Domain\Payment\Flutterwave\Actions\FlutterwaveInitAction;
use App\Models\Transaction;
use App\Services\FlutterwaveService;


class FlutterwaveInitUseCase{

    public function __construct(
        private readonly FlutterwaveInitAction $initAction,
        private readonly FlutterwaveService $flutterwaveService,
    )
    {

    }

    public function run(array $data): array
    {
        $txRef = $this->flutterwaveService->generateReference();

        $data['tx_ref']   = $txRef;
        $data['provider'] = 'flutterwave';

        $this->initAction->run($data);

        // Return everything the frontend popup needs
        return [
            'status' => 'success',
            'data' => [
                'tx_ref' => $txRef,
                'amount' => $data['amount'],
                'currency' => 'NGN',
                'public_key' => config('services.flutterwave.public_key'),
                'customer' => [
                    'name' => $data['customer_name'],
                    'email' => $data['customer_email'],
                    'phone' => $data['customer_phone'],
                ],
                'customizations' => [
                    'title' => 'Order Payment',
                    'description' => 'Payment for your order',
                ],
            ],
        ];
    }
}
