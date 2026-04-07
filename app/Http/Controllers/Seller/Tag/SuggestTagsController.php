<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Tag;

use App\Domain\Seller\Actions\Tag\SuggestTagsAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SuggestTagsController extends Controller
{
    public function __construct(private readonly SuggestTagsAction $action) {}

    public function __invoke(Request $request): JsonResponse
    {
        return ApiResponse::success($this->action->run($request->string('q')));
    }
}
