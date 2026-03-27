<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Seller;

use App\Domain\Admin\Actions\UnsuspendUserAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;

class UnsuspendSellerController extends Controller
{
    public function __construct(private readonly UnsuspendUserAction $action) {}

    public function __invoke(Seller $seller): JsonResponse
    {
        $this->action->run($seller->user);

        return ApiResponse::success(message: 'Seller unsuspended.');
    }
}
