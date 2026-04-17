<?php

declare(strict_types=1);

namespace App\Domain\Order\Actions;

use App\Domain\Shared\Actions\CreateAppLogAction;
use App\Exceptions\BusinessException;
use App\Models\Order;

class DownloadOrderAction
{
    public function __construct(private readonly CreateAppLogAction $log) {}

    public function run(string $token): string
    {
        $order = Order::where('download_token', $token)->first();

        if (!$order || !$order->isPaid()) {
            $this->log->run('warning', 'DOWNLOAD_INVALID_TOKEN', 'Download attempted with invalid or unpaid token.', ['token' => $token]);
            throw new BusinessException('Invalid download link.', 404);
        }

        if ($order->isExpired()) {
            $this->log->run('warning', 'DOWNLOAD_LINK_EXPIRED', 'Download attempted with expired token.', ['order_id' => $order->id, 'token' => $token]);
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
