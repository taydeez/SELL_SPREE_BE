<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Seller;

use App\Domain\Admin\Actions\User\SuspendUserAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;

class SuspendSellerController extends Controller
{
    public function __construct(private readonly SuspendUserAction $action) {}

    public function __invoke(Seller $seller): JsonResponse
    {
        $this->action->run($seller->user, 'seller');

        return ApiResponse::success(message: 'Seller suspended.');
    }
}
