<?php

declare(strict_types=1);

namespace App\Domain\Order\Actions;

use App\Exceptions\BusinessException;
use App\Models\Order;

class DownloadOrderAction
{
    public function run(string $token): string
    {
        $order = Order::where('download_token', $token)->first();

        if (!$order || !$order->isPaid()) {
            throw new BusinessException('Invalid download link.', 404);
        }

        if ($order->isExpired()) {
            throw new BusinessException('This download link has expired.', 410);
        }

        $order->load('product');

        $file = $order->product->productFiles()
            ->where('collection', 'product_file')
            ->first();

        if (!$file || !$file->path) {
            throw BusinessException::notFound('File');
        }

        return $file->signed_url;
    }
}
