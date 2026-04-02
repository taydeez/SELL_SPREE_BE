<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Tag;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SuggestTagsController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $tags = Tag::where('name', 'LIKE', $request->string('q') . '%')
            ->withCount('products')
            ->orderByDesc('products_count')
            ->limit(10)
            ->get(['id', 'name', 'slug']);

        return response()->json(['success' => true, 'data' => $tags]);
    }
}
