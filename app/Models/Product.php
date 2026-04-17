<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Traits\HasSlug;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, HasSlug, HasUlid, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'seller_id',
        'title',
        'slug',
        'description',
        'type',
        'price',
        'status',
        'affiliate_enabled',
        'affiliate_commission_rate',
        'sales_count',
        'view_count',
    ];

    protected function casts(): array
    {
        return [
            'type'        => ProductType::class,
            'status'      => ProductStatus::class,
            'price'             => 'integer',
            'affiliate_enabled'        => 'boolean',
            'affiliate_commission_rate' => 'integer',
            'sales_count'              => 'integer',
            'view_count'        => 'integer',
        ];
    }

    protected static function slugSourceColumn(): string
    {
        return 'title';
    }

    // ─── Media ───────────────────────────────────────────────────────────────

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('cover')
            ->singleFile()
            ->useDisk('r2')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/webp']);

        $this->addMediaCollection('product_file')
            ->singleFile()
            ->useDisk('r2');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('cover')
            ->width(800)
            ->height(800)
            ->format('jpg')
            ->performOnCollections('cover');
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class)->orderBy('sort_order');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function affiliateLinks(): HasMany
    {
        return $this->hasMany(AffiliateLink::class);
    }

    public function productFiles(): HasMany
    {
        return $this->hasMany(ProductFile::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function event(): HasOne
    {
        return $this->hasOne(ProductEvent::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeActive($query)
    {
        return $query->where('status', ProductStatus::Active->value);
    }

    public function scopeByType($query, ProductType $type)
    {
        return $query->where('type', $type->value);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function isTicket(): bool
    {
        return $this->type === ProductType::Ticket;
    }
}
