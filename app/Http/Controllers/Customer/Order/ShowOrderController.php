<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Order;

use App\Domain\Customer\Actions\GetCustomerOrderAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\Customer\CustomerOrderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ShowOrderController extends Controller
{
    public function __construct(private readonly GetCustomerOrderAction $action) {}

    public function __invoke(string $order): JsonResponse
    {
        $email  = Auth::guard('customer')->user()->email;
        $result = $this->action->run($order, $email);

        return ApiResponse::success(new CustomerOrderResource($result));
    }
}
