<?php

declare(strict_types=1);

namespace App\Services;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Support\Facades\Storage;

class QrCodeService
{
    public function generate(string $url, string $path): string
    {
        $qrCode = QrCode::create($url)
            ->setSize(400)
            ->setMargin(10);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        Storage::disk('r2')->put($path, $result->getString(), 'private');

        return $path;
    }

    public function temporaryUrl(string $path): string
    {
        return Storage::disk('r2')->temporaryUrl($path, now()->addHour());
    }
}
