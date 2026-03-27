<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Product;

use App\Domain\Seller\Actions\UploadProductFileAction;
use App\Exceptions\BusinessException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\UploadProductFileRequest;
use App\Http\Resources\ApiResponse;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UploadFileController extends Controller
{
    public function __construct(private readonly UploadProductFileAction $action) {}

    public function __invoke(UploadProductFileRequest $request, Product $product): JsonResponse
    {
        $seller = Seller::where('user_id', Auth::guard('seller')->id())->firstOrFail();

        if ($product->seller_id !== $seller->id) {
            throw BusinessException::forbidden();
        }

        $media = $this->action->run($product, $request->file('file'));

        return ApiResponse::success(['media_id' => $media->id, 'file_name' => $media->file_name]);
    }
}
