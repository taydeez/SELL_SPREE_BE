<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Affiliate;

use App\Domain\Admin\Actions\Affiliate\DeleteAffiliateAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Affiliate;
use Illuminate\Http\JsonResponse;

class DeleteAffiliateController extends Controller
{
    public function __construct(private readonly DeleteAffiliateAction $action) {}

    public function __invoke(Affiliate $affiliate): JsonResponse
    {
        $this->action->run($affiliate);

        return ApiResponse::noContent();
    }
}
