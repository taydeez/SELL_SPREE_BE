<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Event;

use App\Domain\Order\Actions\CheckInAttendeeAction;
use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Exceptions\BusinessException;
use App\Http\Resources\Seller\CheckInResource;
use App\Models\Order;
use App\Models\Product;

class CheckInAttendeeUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly CheckInAttendeeAction $checkIn,
        private readonly CreateAppLogAction $log,
    ) {}

    public function run(int|string $userId, Product $product, string $ticketNumber): CheckInResource
    {
        $seller = $this->resolveSeller->run($userId);

        if ($product->seller_id !== $seller->id) {
            $this->log->run('warning', 'CHECKIN_FORBIDDEN', 'Seller attempted to check in ticket for another seller\'s product.', ['seller_id' => $seller->id, 'product_id' => $product->id, 'ticket_number' => $ticketNumber]);
            throw BusinessException::forbidden();
        }

        $order = Order::where('ticket_number', $ticketNumber)
            ->where('product_id', $product->id)
            ->firstOrFail();

        return new CheckInResource($this->checkIn->run($order));
    }
}
