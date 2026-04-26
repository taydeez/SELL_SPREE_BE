<?php

declare(strict_types=1);

namespace App\Domain\Seller\UseCases\Order;

use App\Domain\Seller\Actions\Order\ShowSellerOrderAction;
use App\Domain\Seller\Actions\ResolveCurrentSellerAction;
use App\Http\Resources\Seller\SellerOrderResource;
use App\Models\Order;

class ShowOrderUseCase
{
    public function __construct(
        private readonly ResolveCurrentSellerAction $resolveSeller,
        private readonly ShowSellerOrderAction $showOrder,
    ) {}

    public function run(int|string $userId, Order $order): SellerOrderResource
    {
        $seller = $this->resolveSeller->run($userId);

        return new SellerOrderResource($this->showOrder->run($seller, $order));
    }
}
