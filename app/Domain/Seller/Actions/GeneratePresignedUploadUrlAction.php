<?php

declare(strict_types=1);

namespace App\Domain\Seller\Actions;

use App\Models\Product;
use Aws\S3\S3Client;
use Illuminate\Support\Str;

class GeneratePresignedUploadUrlAction
{
    public function run(Product $product, array $data): array
    {
        $collection = $data['collection'];
        $mimeType   = $data['mime_type'];
        $fileName   = $data['file_name'];
        $extension  = pathinfo($fileName, PATHINFO_EXTENSION);

        $objectKey = sprintf(
            'products/%s/%s/%s%s',
            $product->id,
            $collection,
            (string) Str::uuid(),
            $extension ? '.' . strtolower($extension) : '',
        );

        $client = new S3Client([
            'version'                 => 'latest',
            'region'                  => 'auto',
            'endpoint'                => config('filesystems.disks.r2.endpoint'),
            'use_path_style_endpoint' => true,
            'credentials'             => [
                'key'    => config('filesystems.disks.r2.key'),
                'secret' => config('filesystems.disks.r2.secret'),
            ],
        ]);

        $cmd = $client->getCommand('PutObject', [
            'Bucket'      => config('filesystems.disks.r2.bucket'),
            'Key'         => $objectKey,
            'ContentType' => $mimeType,
        ]);

        $expiresAt = now()->addHour();
        $request   = $client->createPresignedRequest($cmd, $expiresAt->toDateTimeString());

        return [
            'presigned_url' => (string) $request->getUri(),
            'object_key'    => $objectKey,
            'expires_at'    => $expiresAt->toIso8601String(),
        ];
    }
}
