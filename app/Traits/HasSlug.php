<?php

declare(strict_types=1);

namespace App\Traits;

use Illuminate\Support\Str;

trait HasSlug
{
    protected static function bootHasSlug(): void
    {
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = static::generateUniqueSlug($model);
            }
        });
    }

    protected static function slugSourceColumn(): string
    {
        return 'name';
    }

    protected static function generateUniqueSlug($model): string
    {
        $source = $model->{static::slugSourceColumn()} ?? '';
        $base = Str::slug($source);

        if (empty($base)) {
            $base = Str::lower(Str::random(8));
        }

        $slug = $base;
        $count = 1;

        while (
            static::where('slug', $slug)
                ->where('id', '!=', $model->id ?? '')
                ->exists()
        ) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }
}
