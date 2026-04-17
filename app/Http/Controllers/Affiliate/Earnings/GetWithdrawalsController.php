<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Earnings;

use App\Domain\Affiliate\UseCases\Earnings\GetWithdrawalsUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\Affiliate\WithdrawalResource;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GetWithdrawalsController extends Controller
{
    public function __construct(private readonly GetWithdrawalsUseCase $useCase) {}

    public function __invoke(): JsonResponse
    {
        $result = $this->useCase->run(Auth::guard('affiliate')->user());

        return ApiResponse::success([
            'balance'     => $result['balance'],
            'bank'        => $result['bank'],
            'withdrawals' => WithdrawalResource::collection($result['withdrawals']),
            'meta'        => [
                'current_page' => $result['withdrawals']->currentPage(),
                'last_page'    => $result['withdrawals']->lastPage(),
                'total'        => $result['withdrawals']->total(),
            ],
        ]);
    }
}
