<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Seller extends Model implements HasMedia
{
    use HasFactory, HasUlid, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'user_id',
        'store_name',
        'store_slug',
        'bio',
        'avatar_path',
        'payout_email',
        'commission_rate',
        'is_approved',
        'bank_code',
        'bank_name',
        'account_number',
        'account_name',
        'flw_subaccount_id',
    ];

    protected function casts(): array
    {
        return [
            'is_approved'     => 'boolean',
            'commission_rate' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (Seller $seller) {
            if (empty($seller->store_slug)) {
                $seller->store_slug = static::generateUniqueStoreSlug($seller->store_name);
            }
        });
    }

    protected static function generateUniqueStoreSlug(string $name): string
    {
        $base = Str::slug($name) ?: Str::lower(Str::random(8));
        $slug = $base;
        $count = 1;

        while (static::where('store_slug', $slug)->exists()) {
            $slug = $base . '-' . $count++;
        }

        return $slug;
    }

    // ─── Media ───────────────────────────────────────────────────────────────

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->useDisk('r2');
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function payouts(): MorphMany
    {
        return $this->morphMany(Payout::class, 'payable');
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }
}
