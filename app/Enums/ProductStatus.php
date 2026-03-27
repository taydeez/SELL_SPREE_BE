<?php

declare(strict_types=1);

namespace App\Enums;

enum ProductStatus: string
{
    case Draft = 'draft';
    case Active = 'active';
    case Paused = 'paused';
    case Suspended = 'suspended';

    public function label(): string
    {
        return match($this) {
            self::Draft     => 'Draft',
            self::Active    => 'Active',
            self::Paused    => 'Paused',
            self::Suspended => 'Suspended',
        };
    }

    public function isPubliclyVisible(): bool
    {
        return $this === self::Active;
    }
}
