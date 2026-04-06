<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\TransactionResource;
use App\Http\Resources\ApiResponse;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;

class ShowTransactionController extends Controller
{
    public function __invoke(Transaction $transaction): JsonResponse
    {
        return ApiResponse::success(new TransactionResource($transaction));
    }
}
