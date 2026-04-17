<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Affiliate;

use App\Domain\Admin\Actions\Affiliate\ShowAffiliateAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AffiliateDetailResource;
use App\Http\Resources\ApiResponse;
use App\Models\Affiliate;
use Illuminate\Http\JsonResponse;

class ShowAffiliateController extends Controller
{
    public function __construct(private readonly ShowAffiliateAction $action) {}

    public function __invoke(Affiliate $affiliate): JsonResponse
    {
        return ApiResponse::success(new AffiliateDetailResource($this->action->run($affiliate)));
    }
}
