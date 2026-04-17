<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Affiliate;

use App\Domain\Admin\UseCases\Affiliate\ProcessAffiliatePayoutUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\AffiliateWithdrawal;
use Illuminate\Http\JsonResponse;

class ProcessPayoutController extends Controller
{
    public function __construct(private readonly ProcessAffiliatePayoutUseCase $useCase) {}

    public function __invoke(AffiliateWithdrawal $withdrawal): JsonResponse
    {
        return ApiResponse::success($this->useCase->run($withdrawal), 'Payout processed.');
    }
}
