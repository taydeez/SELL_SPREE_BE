<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Seller;

use App\Domain\Admin\Actions\Seller\ApproveSellerAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;

class ApproveSellerController extends Controller
{
    public function __construct(private readonly ApproveSellerAction $action) {}

    public function __invoke(Seller $seller): JsonResponse
    {
        $this->action->run($seller);

        return ApiResponse::success(message: 'Seller approved.');
    }
}
