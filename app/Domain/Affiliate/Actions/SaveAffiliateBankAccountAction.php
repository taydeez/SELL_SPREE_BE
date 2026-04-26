<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\Actions;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Models\Affiliate;
use App\Services\FlutterwaveService;
use Illuminate\Validation\ValidationException;

class SaveAffiliateBankAccountAction
{
    public function __construct(
        private readonly FlutterwaveService $flutterwave,
        private readonly CreateAppLogAction $log,
    ) {}

    public function run(Affiliate $affiliate, array $data): Affiliate
    {
        $resolved = $this->flutterwave->verifyBankAccount([
            'account_number' => $data['account_number'],
            'account_bank'   => $data['bank_code'],
        ]);

        if (($resolved['status'] ?? '') !== 'success' || empty($resolved['data']['account_name'])) {
            $this->log->run('warning', 'AFFILIATE_BANK_VERIFICATION_FAILED', 'Bank account verification failed for affiliate.', [
                'affiliate_id'   => $affiliate->id,
                'bank_code'      => $data['bank_code'],
                'account_number' => $data['account_number'],
            ]);
            throw ValidationException::withMessages([
                'account_number' => ['Could not verify bank account. Please check your details.'],
            ]);
        }

        $accountName = $resolved['data']['account_name'];
        $bankName    = $this->resolveBankName($data['bank_code']);

        $affiliate->update([
            'bank_code'      => $data['bank_code'],
            'bank_name'      => $bankName,
            'account_number' => $data['account_number'],
            'account_name'   => $accountName,
        ]);

        $this->log->run('info', 'AFFILIATE_BANK_SAVED', 'Affiliate bank account saved.', [
            'affiliate_id' => $affiliate->id,
            'bank_name'    => $bankName,
            'account_name' => $accountName,
        ]);

        return $affiliate->fresh();
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
