<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\SellerListResource;
use App\Http\Resources\ApiResponse;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;

class ShowSellerController extends Controller
{
    public function __invoke(Seller $seller): JsonResponse
    {
        $seller->load('user');

        return ApiResponse::success(new SellerListResource($seller));
    }
}
