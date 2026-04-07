<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public\Product;

use App\Domain\Product\Actions\ShowPublicProductAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\Public\PublicProductResource;
use Illuminate\Http\JsonResponse;

class ShowPublicProductController extends Controller
{
    public function __construct(private readonly ShowPublicProductAction $action) {}

    public function __invoke(string $slug): JsonResponse
    {
        return ApiResponse::success(new PublicProductResource($this->action->run($slug)));
    }
}
