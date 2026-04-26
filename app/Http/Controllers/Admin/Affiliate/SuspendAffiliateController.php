<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Affiliate;

use App\Domain\Admin\Actions\User\SuspendUserAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Affiliate;
use Illuminate\Http\JsonResponse;

class SuspendAffiliateController extends Controller
{
    public function __construct(private readonly SuspendUserAction $action) {}

    public function __invoke(Affiliate $affiliate): JsonResponse
    {
        $this->action->run($affiliate->user, 'affiliate');

        return ApiResponse::success(message: 'Affiliate suspended.');
    }
}
