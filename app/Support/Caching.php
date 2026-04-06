<?php
/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */


namespace App\Support;
use Illuminate\Support\Facades\Cache;

final class Caching
{
    public static function flushProducts(): void
    {
        Cache::tags(['catalog', 'products'])->flush();
    }

    public static function flushOrders(): void
    {
        Cache::tags(['catalog', 'categories'])->flush();
    }

    public static function flushSellers(): void
    {
        Cache::tags(['se', 'brands'])->flush();
    }

    public static function flushAll(): void
    {
        Cache::tags(['catalog'])->flush();
    }
}
