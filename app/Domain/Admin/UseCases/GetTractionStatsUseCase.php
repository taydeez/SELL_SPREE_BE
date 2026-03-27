<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases;

use App\Enums\UserRole;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GetTractionStatsUseCase
{
    public function run(): array
    {
        $daily = Order::paid()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as sales'), DB::raw('SUM(amount) as revenue'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $newSellers = User::byRole(UserRole::Seller)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $newAffiliates = User::byRole(UserRole::Affiliate)
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'daily_sales'    => $daily,
            'new_sellers'    => $newSellers,
            'new_affiliates' => $newAffiliates,
        ];
    }
}
