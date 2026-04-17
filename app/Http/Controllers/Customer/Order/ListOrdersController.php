<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Order;

use App\Domain\Customer\Actions\ListCustomerOrdersAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\Customer\CustomerOrderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListOrdersController extends Controller
{
    public function __construct(private readonly ListCustomerOrdersAction $action) {}

    public function __invoke(Request $request): JsonResponse
    {
        $email  = Auth::guard('customer')->user()->email;
        $orders = $this->action->run($email, $request->query('status'));

        return ApiResponse::success(
            CustomerOrderResource::collection($orders),
        );
    }
}
