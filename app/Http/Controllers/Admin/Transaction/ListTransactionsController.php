<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\TransactionResource;
use App\Http\Resources\ApiResponse;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListTransactionsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $transactions = Transaction::query()
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->search, fn ($q) => $q->where(function ($q) use ($request) {
                $q->where('customer_email', 'like', "%{$request->search}%")
                  ->orWhere('tx_ref', 'like', "%{$request->search}%")
                  ->orWhere('transaction_id', 'like', "%{$request->search}%");
            }))
            ->latest()
            ->paginate(20);


        return ApiResponse::success(TransactionResource::collection($transactions));
    }
}
