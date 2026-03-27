<?php

declare(strict_types=1);

namespace App\Enums;

enum ProductType: string
{
    case Ebook = 'ebook';
    case Music = 'music';
    case Video = 'video';
    case Code = 'code';
    case Ticket = 'ticket';

    public function label(): string
    {
        return match($this) {
            self::Ebook  => 'eBook',
            self::Music  => 'Music',
            self::Video  => 'Short Video',
            self::Code   => 'Code',
            self::Ticket => 'Event Ticket',
        };
    }

    public function allowedMimeTypes(): array
    {
        return match($this) {
            self::Ebook  => ['application/pdf', 'application/epub+zip'],
            self::Music  => ['audio/mpeg', 'audio/wav', 'audio/flac', 'audio/x-flac'],
            self::Video  => ['video/mp4', 'video/quicktime'],
            self::Code   => ['application/zip', 'application/x-tar', 'application/gzip', 'application/x-gzip'],
            self::Ticket => [],
        };
    }

    public function hasVariants(): bool
    {
        return match($this) {
            self::Music, self::Code, self::Ticket => true,
            default => false,
        };
    }

    public function hasTicketQr(): bool
    {
        return $this === self::Ticket;
    }
}
