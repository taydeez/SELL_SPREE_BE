<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Affiliate;

use App\Domain\Admin\Actions\UpdateAffiliateCommissionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateAffiliateCommissionRequest;
use App\Http\Resources\ApiResponse;
use App\Models\Affiliate;
use Illuminate\Http\JsonResponse;

class UpdateCommissionController extends Controller
{
    public function __construct(private readonly UpdateAffiliateCommissionAction $action) {}

    public function __invoke(UpdateAffiliateCommissionRequest $request, Affiliate $affiliate): JsonResponse
    {
        $this->action->run($affiliate, $request->commission_rate);

        return ApiResponse::success(message: 'Commission rate updated.');
    }
}
