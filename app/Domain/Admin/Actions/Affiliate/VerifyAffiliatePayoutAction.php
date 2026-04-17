<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Affiliate;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Exceptions\BusinessException;
use App\Models\AffiliateSale;
use App\Models\AffiliateWithdrawal;
use App\Services\FlutterwaveService;
use Illuminate\Support\Facades\DB;

class VerifyAffiliatePayoutAction
{
    public function __construct(
        private readonly FlutterwaveService $flutterwave,
        private readonly CreateAppLogAction $log,
    ) {}

    public function run(AffiliateWithdrawal $withdrawal): AffiliateWithdrawal
    {
        if (! $withdrawal->isProcessing()) {
            throw BusinessException::invalidOperation('Only processing withdrawals can be verified.');
        }

        $result = $this->flutterwave->verifyTransfer($withdrawal->flw_transfer_id);

        $status = strtolower($result['data']['status'] ?? '');

        if ($status === 'successful') {
            DB::transaction(function () use ($withdrawal) {
                $withdrawal->update(['status' => 'paid']);

                AffiliateSale::where('affiliate_id', $withdrawal->affiliate_id)
                    ->where('status', 'available')
                    ->update(['status' => 'settled', 'settled_at' => now(), 'is_paid' => true]);
            });

            $this->log->run('info', 'AFFILIATE_PAYOUT_VERIFIED', 'Affiliate payout verified and settled.', [
                'withdrawal_id'   => $withdrawal->id,
                'affiliate_id'    => $withdrawal->affiliate_id,
                'amount'          => $withdrawal->amount,
                'flw_transfer_id' => $withdrawal->flw_transfer_id,
            ]);
        } elseif ($status === 'failed') {
            $reason = $result['data']['complete_message'] ?? 'Transfer failed.';

            $withdrawal->update(['status' => 'failed', 'failure_reason' => $reason]);

            $this->log->run('error', 'AFFILIATE_PAYOUT_FAILED', 'Affiliate payout transfer failed on verification.', [
                'withdrawal_id' => $withdrawal->id,
                'reason'        => $reason,
            ]);

            throw BusinessException::invalidOperation("Transfer failed: {$reason}");
        }

        return $withdrawal->fresh();
    }
}
