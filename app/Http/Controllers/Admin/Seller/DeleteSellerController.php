<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Seller;

use App\Domain\Admin\Actions\Seller\DeleteSellerAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;

class DeleteSellerController extends Controller
{
    public function __construct(private readonly DeleteSellerAction $action) {}

    public function __invoke(Seller $seller): JsonResponse
    {
        $this->action->run($seller);

        return ApiResponse::noContent();
    }
}
