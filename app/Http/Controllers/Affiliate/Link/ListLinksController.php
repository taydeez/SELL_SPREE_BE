<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Link;

use App\Domain\Affiliate\UseCases\Link\ListLinksUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ListLinksController extends Controller
{
    public function __construct(private readonly ListLinksUseCase $useCase) {}

    public function __invoke(Request $request): JsonResponse
    {
        return ApiResponse::success($this->useCase->run(Auth::guard('affiliate')->id(), $request->only(['active'])));
    }
}
