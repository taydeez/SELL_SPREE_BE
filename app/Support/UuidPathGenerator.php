<?php

declare(strict_types=1);

namespace App\Support;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

/**
 * Generates R2 paths using the media UUID so we can pre-calculate
 * the exact storage key before the Media record is created.
 * Path format: {uuid}/{file_name}
 */
class UuidPathGenerator implements PathGenerator
{
    public function getPath(Media $media): string
    {
        return $media->uuid . '/';
    }

    public function getPathForConversions(Media $media): string
    {
        return $media->uuid . '/conversions/';
    }

    public function getPathForResponsiveImages(Media $media): string
    {
        return $media->uuid . '/responsive-images/';
    }
}
