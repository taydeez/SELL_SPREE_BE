<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\Actions;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Exceptions\BusinessException;
use App\Models\Affiliate;
use App\Models\AffiliateWithdrawal;

class RequestWithdrawalAction
{
    private const MIN_WITHDRAWAL_CENTS = 50000; // ₦500

    public function __construct(private readonly CreateAppLogAction $log) {}

    public function run(Affiliate $affiliate): AffiliateWithdrawal
    {
        if (! $affiliate->hasBankAccount()) {
            throw BusinessException::invalidOperation('Add your bank account before requesting a withdrawal.');
        }

        $available = $affiliate->availableBalance();

        if ($available < self::MIN_WITHDRAWAL_CENTS) {
            throw BusinessException::invalidOperation('Minimum withdrawal is ₦500. Your available balance is insufficient.');
        }

        $hasPending = AffiliateWithdrawal::where('affiliate_id', $affiliate->id)
            ->whereIn('status', ['pending', 'processing'])
            ->exists();

        if ($hasPending) {
            throw BusinessException::invalidOperation('You already have a pending withdrawal request.');
        }

        $withdrawal = AffiliateWithdrawal::create([
            'affiliate_id'   => $affiliate->id,
            'amount'         => $available,
            'status'         => 'pending',
            'bank_code'      => $affiliate->bank_code,
            'bank_name'      => $affiliate->bank_name,
            'account_number' => $affiliate->account_number,
            'account_name'   => $affiliate->account_name,
        ]);

        $this->log->run('info', 'WITHDRAWAL_REQUESTED', 'Affiliate requested withdrawal.', [
            'affiliate_id'  => $affiliate->id,
            'withdrawal_id' => $withdrawal->id,
            'amount'        => $available,
        ]);

        return $withdrawal;
    }
}
