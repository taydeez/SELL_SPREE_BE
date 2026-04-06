<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Event;

use App\Domain\Order\Actions\CheckInAttendeeAction;
use App\Exceptions\BusinessException;
use App\Http\Requests\Seller\CheckInRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\Seller\CheckInResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CheckInAttendeeController
{
    public function __construct(private readonly CheckInAttendeeAction $checkInAttendeeAction) {}

    public function __invoke(CheckInRequest $request, Product $product): JsonResponse
    {
        $seller = Seller::where('user_id', Auth::guard('seller')->id())->firstOrFail();

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        $order = Order::where('ticket_number', $request->validated('ticket_number'))
            ->where('product_id', $product->id)
            ->firstOrFail();

        $order = $this->checkInAttendeeAction->run($order);

        return ApiResponse::success(new CheckInResource($order));
    }
}
