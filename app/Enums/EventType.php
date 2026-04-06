<?php

declare(strict_types=1);

namespace App\Enums;

enum EventType: string
{
    case Online   = 'online';
    case Physical = 'physical';

    public function label(): string
    {
        return match($this) {
            self::Online   => 'Online',
            self::Physical => 'Physical',
        };
    }
}
