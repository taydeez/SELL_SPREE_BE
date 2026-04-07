<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Affiliate;

use App\Domain\Admin\UseCases\Affiliate\ListAffiliatesUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ListAffiliatesController extends Controller
{
    public function __construct(private readonly ListAffiliatesUseCase $useCase) {}

    public function __invoke(Request $request): JsonResponse
    {
        return ApiResponse::success($this->useCase->run($request->only(['search'])));
    }
}
