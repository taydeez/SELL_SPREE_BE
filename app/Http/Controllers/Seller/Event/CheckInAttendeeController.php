<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Event;

use App\Domain\Seller\UseCases\Event\CheckInAttendeeUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\CheckInRequest;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class CheckInAttendeeController extends Controller
{
    public function __construct(private readonly CheckInAttendeeUseCase $useCase) {}

    public function __invoke(CheckInRequest $request, Product $product): JsonResponse
    {
        return ApiResponse::success($this->useCase->run(
            Auth::guard('seller')->id(),
            $product,
            $request->validated('ticket_number'),
        ));
    }
}
