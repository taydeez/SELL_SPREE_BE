<?php
/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */

namespace App\Domain\Payment\Flutterwave\UseCases;

use App\Domain\Payment\Flutterwave\Actions\FlutterwaveInitAction;
use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Models\Order;
use App\Models\Transaction;
use App\Services\FlutterwaveService;


class FlutterwaveInitUseCase{

    public function __construct(
        private readonly FlutterwaveInitAction $initAction,
        private readonly FlutterwaveService $flutterwaveService,
        private readonly CreateAppLogAction $log,
    )
    {

    }

    public function run(array $data): array
    {
        $txRef = $this->flutterwaveService->generateReference();

        $data['tx_ref']   = $txRef;
        $data['provider'] = 'flutterwave';

        // amount arrives in naira from the frontend; transactions table stores cents
        $amountNaira = (float) $data['amount'];
        $data['amount'] = (int) round($amountNaira * 100);

        $this->initAction->run($data);

        // ─── Resolve split subaccount ─────────────────────────────────────────
        $subaccounts = [];

        if (!empty($data['order_id'])) {
            $order = Order::with('seller', 'product')->find($data['order_id']);

            if ($order && $order->seller && $order->seller->flw_subaccount_id) {
                $platformFeeRate      = 10;
                $affiliateCommission  = ($order->affiliate_link_id && $order->product->affiliate_commission_rate > 0)
                    ? (int) $order->product->affiliate_commission_rate
                    : 0;
                $sellerSplitRatio     =  ($platformFeeRate + $affiliateCommission) / 100;

                if ($sellerSplitRatio > 0) {
                    $subaccounts = [
                        [
                            'id'                      => $order->seller->flw_subaccount_id,
                            'transaction_charge_type' => "percentage",
                             'transaction_charge' => $sellerSplitRatio,
                        ],
                    ];

                    $this->log->run('info', 'PAYMENT_SPLIT_RESOLVED', 'Split payment subaccount resolved.', [
                        'tx_ref'                => $txRef,
                        'order_id'              => $order->id,
                        'seller_id'             => $order->seller->id,
                        'flw_subaccount_id'     => $order->seller->flw_subaccount_id,
                        'platform_fee_rate'     => $platformFeeRate,
                        'affiliate_commission'  => $affiliateCommission,
                        'seller_split_ratio'    => $sellerSplitRatio,
                    ]);
                }
            } else {
                $this->log->run('warning', 'PAYMENT_SPLIT_SKIPPED', 'Split skipped: seller has no subaccount.', [
                    'tx_ref'   => $txRef,
                    'order_id' => $data['order_id'],
                ]);
            }
        }

        $this->log->run('info', 'PAYMENT_INITIATED', 'Flutterwave payment initiated.', [
            'tx_ref'       => $txRef,
            'amount_cents' => $data['amount'] ?? null,
            'buyer_email'  => $data['customer_email'] ?? null,
            'order_id'     => $data['order_id'] ?? null,
            'split'        => !empty($subaccounts),
        ]);

        // Return everything the frontend popup needs
        $response = [
            'tx_ref'     => $txRef,
            'amount'     => $amountNaira,  // Flutterwave popup expects naira
            'currency'   => 'NGN',
            'public_key' => config('services.flutterwave.public_key'),
            'customer'   => [
                'name'  => $data['customer_name'],
                'email' => $data['customer_email'],
                'phone' => $data['customer_phone'],
            ],
            'customizations' => [
                'title'       => 'Order Payment',
                'description' => 'Payment for your order',
            ],
        ];

        if (!empty($subaccounts)) {
            $response['subaccounts'] = $subaccounts;
        }

        return $response;
    }
}
