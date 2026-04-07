<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Link;

use App\Domain\Affiliate\UseCases\Link\GenerateLinkUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Affiliate\GenerateAffiliateLinkRequest;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GenerateLinkController extends Controller
{
    public function __construct(private readonly GenerateLinkUseCase $useCase) {}

    public function __invoke(GenerateAffiliateLinkRequest $request): JsonResponse
    {
        return ApiResponse::created($this->useCase->run(Auth::guard('affiliate')->id(), $request->product_id));
    }
}
