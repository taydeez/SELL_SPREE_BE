<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;

class DeleteSellerController extends Controller
{
    public function __invoke(Seller $seller): JsonResponse
    {
        $seller->user->delete();
        $seller->delete();

        return ApiResponse::noContent();
    }
}
