<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public\Order;

use App\Http\Resources\ApiResponse;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class DownloadOrderController
{
    public function __invoke(string $token): RedirectResponse|JsonResponse
    {
        $order = Order::where('download_token', $token)->first();

        if (!$order || !$order->isPaid()) {
            return ApiResponse::error('Invalid download link.', null, 404);
        }

        if ($order->isExpired()) {
            return ApiResponse::error('This download link has expired.', null, 410);
        }

        $order->load('product');

        $file = $order->product->productFiles()
            ->where('collection', 'product_file')
            ->first();

        if (!$file || !$file->path) {
            return ApiResponse::error('File not found.', null, 404);
        }

        return redirect()->away($file->signed_url);
    }
}
