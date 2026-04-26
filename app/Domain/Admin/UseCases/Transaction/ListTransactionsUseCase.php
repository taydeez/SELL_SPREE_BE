<?php

declare(strict_types=1);

namespace App\Domain\Admin\UseCases\Transaction;

use App\Domain\Admin\Actions\Transaction\ListTransactionsAction;
use App\Http\Resources\Admin\TransactionResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ListTransactionsUseCase
{
    public function __construct(private readonly ListTransactionsAction $action) {}

    public function run(array $filters): ResourceCollection
    {
        return TransactionResource::collection($this->action->run(
            $filters['status'] ?? null,
            $filters['search'] ?? null,
        ));
    }
}
