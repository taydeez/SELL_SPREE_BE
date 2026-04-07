<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public\Affiliate;

use App\Domain\Order\Actions\TrackAffiliateLinkAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class TrackAffiliateLinkController extends Controller
{
    public function __construct(private readonly TrackAffiliateLinkAction $action) {}

    public function __invoke(string $slug): JsonResponse
    {
        return ApiResponse::success($this->action->run($slug));
    }
}
