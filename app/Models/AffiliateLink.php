<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class AffiliateLink extends Model
{
    use HasFactory, HasUlid;

    protected $fillable = [
        'affiliate_id',
        'product_id',
        'slug',
        'is_active',
        'view_count',
        'click_count',
        'sales_count',
        'total_earned',
    ];

    protected function casts(): array
    {
        return [
            'is_active'   => 'boolean',
            'view_count'  => 'integer',
            'click_count' => 'integer',
            'sales_count' => 'integer',
            'total_earned' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (AffiliateLink $link) {
            if (empty($link->slug)) {
                $link->slug = static::generateUniqueSlug();
            }
        });
    }

    protected static function generateUniqueSlug(): string
    {
        do {
            $slug = Str::lower(Str::random(10));
        } while (static::where('slug', $slug)->exists());

        return $slug;
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(AffiliateSale::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
