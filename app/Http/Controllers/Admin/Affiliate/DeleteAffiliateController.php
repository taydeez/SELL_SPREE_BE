<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Affiliate;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Affiliate;
use Illuminate\Http\JsonResponse;

class DeleteAffiliateController extends Controller
{
    public function __invoke(Affiliate $affiliate): JsonResponse
    {
        $affiliate->user->delete();
        $affiliate->delete();

        return ApiResponse::noContent();
    }
}
