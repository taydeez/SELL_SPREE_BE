<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Earnings;

use App\Domain\Affiliate\UseCases\Earnings\RequestWithdrawalUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\Affiliate\WithdrawalResource;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class RequestWithdrawalController extends Controller
{
    public function __construct(private readonly RequestWithdrawalUseCase $useCase) {}

    public function __invoke(): JsonResponse
    {
        $withdrawal = $this->useCase->run(Auth::guard('affiliate')->user());

        return ApiResponse::success(new WithdrawalResource($withdrawal), 'Withdrawal request submitted.');
    }
}
