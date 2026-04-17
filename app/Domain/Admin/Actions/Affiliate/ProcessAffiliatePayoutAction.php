<?php

declare(strict_types=1);

namespace App\Domain\Admin\Actions\Affiliate;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Exceptions\BusinessException;
use App\Models\AffiliateSale;
use App\Models\AffiliateWithdrawal;
use App\Services\FlutterwaveService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProcessAffiliatePayoutAction
{
    public function __construct(
        private readonly FlutterwaveService $flutterwave,
        private readonly CreateAppLogAction $log,
    ) {}

    public function run(AffiliateWithdrawal $withdrawal): AffiliateWithdrawal
    {
        if (! $withdrawal->isPending()) {
            throw BusinessException::invalidOperation('Only pending withdrawals can be processed.');
        }

        $reference = 'PAY-' . strtoupper(Str::random(10)) . '-' . time();

        $result = $this->flutterwave->initiateTransfer([
            'bank_code'      => $withdrawal->bank_code,
            'account_number' => $withdrawal->account_number,
            'amount'         => $withdrawal->amount,
            'reference'      => $reference,
            'narration'      => 'Affiliate earnings payout',
        ]);

        if (($result['status'] ?? '') !== 'success') {
            $reason = $result['message'] ?? 'Transfer initiation failed.';

            $withdrawal->update([
                'status'         => 'failed',
                'failure_reason' => $reason,
            ]);

            $this->log->run('error', 'AFFILIATE_PAYOUT_FAILED', 'Flutterwave transfer initiation failed.', [
                'withdrawal_id' => $withdrawal->id,
                'affiliate_id'  => $withdrawal->affiliate_id,
                'reason'        => $reason,
            ]);

            throw BusinessException::invalidOperation($reason);
        }

        $transferId     = (string) ($result['data']['id'] ?? '');
        $transferStatus = strtolower($result['data']['status'] ?? '');

        DB::transaction(function () use ($withdrawal, $reference, $transferId, $transferStatus) {
            if ($transferStatus === 'successful') {
                $withdrawal->update([
                    'status'          => 'paid',
                    'flw_transfer_id' => $transferId,
                    'flw_reference'   => $reference,
                ]);

                AffiliateSale::where('affiliate_id', $withdrawal->affiliate_id)
                    ->where('status', 'available')
                    ->update(['status' => 'settled', 'settled_at' => now(), 'is_paid' => true]);
            } else {
                $withdrawal->update([
                    'status'          => 'processing',
                    'flw_transfer_id' => $transferId,
                    'flw_reference'   => $reference,
                ]);
            }
        });

        $this->log->run('info', 'AFFILIATE_PAYOUT_INITIATED', 'Affiliate payout initiated via Flutterwave.', [
            'withdrawal_id'   => $withdrawal->id,
            'affiliate_id'    => $withdrawal->affiliate_id,
            'amount'          => $withdrawal->amount,
            'flw_transfer_id' => $transferId,
            'transfer_status' => $transferStatus,
        ]);

        return $withdrawal->fresh();
    }
}
