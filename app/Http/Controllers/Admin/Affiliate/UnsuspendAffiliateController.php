<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Affiliate;

use App\Domain\Admin\Actions\User\UnsuspendUserAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Affiliate;
use Illuminate\Http\JsonResponse;

class UnsuspendAffiliateController extends Controller
{
    public function __construct(private readonly UnsuspendUserAction $action) {}

    public function __invoke(Affiliate $affiliate): JsonResponse
    {
        $this->action->run($affiliate->user, 'affiliate');

        return ApiResponse::success(message: 'Affiliate unsuspended.');
    }
}
