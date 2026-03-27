<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Link;

use App\Domain\Affiliate\Actions\GenerateAffiliateLinkAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Affiliate\GenerateAffiliateLinkRequest;
use App\Http\Resources\Affiliate\AffiliateLinkResource;
use App\Http\Resources\ApiResponse;
use App\Models\Affiliate;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GenerateLinkController extends Controller
{
    public function __construct(private readonly GenerateAffiliateLinkAction $action) {}

    public function __invoke(GenerateAffiliateLinkRequest $request): JsonResponse
    {
        $affiliate = Affiliate::where('user_id', Auth::guard('affiliate')->id())->firstOrFail();
        $product   = Product::findOrFail($request->product_id);

        $link = $this->action->run($affiliate, $product);

        return ApiResponse::created(new AffiliateLinkResource($link->load('product')));
    }
}
