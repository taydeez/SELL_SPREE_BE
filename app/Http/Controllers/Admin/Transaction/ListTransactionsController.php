<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Transaction;

use App\Domain\Admin\UseCases\Transaction\ListTransactionsUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListTransactionsController extends Controller
{
    public function __construct(private readonly ListTransactionsUseCase $useCase) {}

    public function __invoke(Request $request): JsonResponse
    {
        return ApiResponse::success($this->useCase->run($request->only(['status', 'search'])));
    }
}
