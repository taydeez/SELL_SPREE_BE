<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Models\Seller;
use App\Services\FlutterwaveService;
use Illuminate\Validation\ValidationException;

class SaveSellerBankAccountAction
{
    public function __construct(
        private readonly FlutterwaveService $flutterwave,
        private readonly CreateAppLogAction $log,
    ) {}

    public function run(Seller $seller, array $data): Seller
    {
        // 1. Verify the bank account exists
        $resolved = $this->flutterwave->verifyBankAccount([
            'account_number' => $data['account_number'],
            'account_bank'   => $data['bank_code'],
        ]);

        if (($resolved['status'] ?? '') !== 'success' || empty($resolved['data']['account_name'])) {
            $this->log->run('warning', 'BANK_VERIFICATION_FAILED', ' '.$resolved['message'].' :: ', ['seller_id' => $seller->id, 'bank_code' => $data['bank_code'], 'account_number' => $data['account_number']]);
            throw ValidationException::withMessages([
                'account_number' => [$resolved['message']],
            ]);
        }

        $accountName = $resolved['data']['account_name'];

        // 2. Fetch the bank name from the bank code
        $bankName = $this->resolveBankName($data['bank_code']);



        $platform_fee = 10;

        $base_seller_commission = 100 - $platform_fee;

        $splitValue = round(($base_seller_commission) / 100, 4);

        $subaccountPayload = [
            'account_bank'    => $data['bank_code'],
            'account_number'  => $data['account_number'],
            'business_name'   => $seller->store_name,
            'country'         => 'NG',
            'split_type'      => 'percentage',
            'split_value'     => $splitValue,
            'business_mobile' => $data['business_mobile'],
            'business_email'  => $seller->user->email,
            'business_contact'=> $seller->user->name,
        ];

        $subaccount = $this->flutterwave->createSubAccount($subaccountPayload);

        if (($subaccount['status'] ?? '') !== 'success' || empty($subaccount['data']['subaccount_id'])) {
            $this->log->run('error', 'SUBACCOUNT_CREATE_FAILED', ' '.$resolved['message'].' :: ', ['seller_id' => $seller->id, 'bank_code' => $data['bank_code']]);
            throw ValidationException::withMessages([
                'account_number' => [$subaccount['message']],
            ]);
        }

        // 4. Persist
        $seller->update([
            'bank_code'         => $data['bank_code'],
            'bank_name'         => $bankName,
            'account_number'    => $data['account_number'],
            'account_name'      => $accountName,
            'flw_subaccount_id' => $subaccount['data']['subaccount_id'],
        ]);

        $this->log->run('info', 'SELLER_BANK_SAVED', 'Seller bank account saved and subaccount created.', ['seller_id' => $seller->id, 'bank_name' => $bankName, 'account_name' => $accountName]);

        return $seller->fresh();
    }

    private function resolveBankName(string $bankCode): string
    {
        $banks = $this->flutterwave->getBanks('NG');

        if (($banks['status'] ?? '') === 'success') {
            foreach ($banks['data'] as $bank) {
                if ((string) $bank['code'] === $bankCode) {
                    return $bank['name'];
                }
            }
        }

        return $bankCode;
    }
}
