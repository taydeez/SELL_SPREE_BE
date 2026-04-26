<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasSlug;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Affiliate extends Model implements HasMedia
{
    use HasFactory, HasSlug, HasUlid, InteractsWithMedia, SoftDeletes;

    protected $fillable = [
        'user_id',
        'display_name',
        'slug',
        'payout_email',
        'commission_rate',
        'bank_code',
        'bank_name',
        'account_number',
        'account_name',
        'flw_recipient_id',
    ];

    protected function casts(): array
    {
        return [
            'commission_rate' => 'integer',
        ];
    }

    protected static function slugSourceColumn(): string
    {
        return 'display_name';
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

    public function links(): HasMany
    {
        return $this->hasMany(AffiliateLink::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(AffiliateSale::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(AffiliateWithdrawal::class);
    }

    public function payouts(): MorphMany
    {
        return $this->morphMany(Payout::class, 'payable');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function hasBankAccount(): bool
    {
        return (bool) $this->account_number;
    }

    public function availableBalance(): int
    {
        return (int) $this->sales()->where('status', 'available')->sum('commission_amount');
    }
}
