<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Affiliate;

use App\Domain\Admin\UseCases\Affiliate\VerifyAffiliatePayoutUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\AffiliateWithdrawal;
use Illuminate\Http\JsonResponse;

class VerifyPayoutController extends Controller
{
    public function __construct(private readonly VerifyAffiliatePayoutUseCase $useCase) {}

    public function __invoke(AffiliateWithdrawal $withdrawal): JsonResponse
    {
        return ApiResponse::success($this->useCase->run($withdrawal), 'Payout verified.');
    }
}
