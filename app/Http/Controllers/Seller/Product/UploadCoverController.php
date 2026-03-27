<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Product;

use App\Domain\Seller\Actions\UploadProductCoverAction;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\UploadProductCoverRequest;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UploadCoverController extends Controller
{
    public function __construct(private readonly UploadProductCoverAction $action) {}

    public function __invoke(UploadProductCoverRequest $request, Product $product): JsonResponse
    {
        $seller = Seller::where('user_id', Auth::guard('seller')->id())->firstOrFail();

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        $media = $this->action->run($product, $request->file('cover'));

        return ApiResponse::success(['url' => $media->getUrl()]);
    }
}
