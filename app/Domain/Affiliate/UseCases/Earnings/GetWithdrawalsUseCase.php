<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\UseCases\Earnings;

use App\Domain\Affiliate\Actions\ResolveCurrentAffiliateAction;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class GetWithdrawalsUseCase
{
    public function __construct(
        private readonly ResolveCurrentAffiliateAction $resolveAffiliate,
    ) {}

    public function run(User $user): array
    {
        $affiliate = $this->resolveAffiliate->run($user->id);

        $withdrawals = $affiliate->withdrawals()->latest()->paginate(20);

        return [
            'balance' => [
                'lifetime'  => (int) $affiliate->sales()->sum('commission_amount'),
                'available' => $affiliate->availableBalance(),
                'pending'   => (int) $affiliate->sales()->where('status', 'pending')->sum('commission_amount'),
                'settled'   => (int) $affiliate->sales()->where('status', 'settled')->sum('commission_amount'),
            ],
            'bank' => $affiliate->hasBankAccount() ? [
                'bank_name'      => $affiliate->bank_name,
                'account_name'   => $affiliate->account_name,
                'account_number' => $affiliate->account_number,
            ] : null,
            'withdrawals' => $withdrawals,
        ];
    }
}
