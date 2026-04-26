<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Link;

use App\Domain\Affiliate\UseCases\Link\EnableLinkUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\AffiliateLink;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class EnableLinkController extends Controller
{
    public function __construct(private readonly EnableLinkUseCase $useCase) {}

    public function __invoke(AffiliateLink $affiliateLink): JsonResponse
    {
        $this->useCase->run(Auth::guard('affiliate')->id(), $affiliateLink);

        return ApiResponse::success(message: 'Link enabled.');
    }
}
