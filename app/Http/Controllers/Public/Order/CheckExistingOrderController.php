<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public\Order;

use App\Domain\Order\Actions\CheckExistingOrderAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Public\Order\CheckExistingOrderRequest;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class CheckExistingOrderController extends Controller
{
    public function __construct(private readonly CheckExistingOrderAction $action) {}

    public function __invoke(CheckExistingOrderRequest $request): JsonResponse
    {
        $alreadyPaid = $this->action->run($request->email, $request->product_slug);

        return ApiResponse::success(['already_paid' => $alreadyPaid]);
    }
}
