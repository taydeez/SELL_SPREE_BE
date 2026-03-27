<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderStatus;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory, HasUlid;

    protected $fillable = [
        'buyer_email',
        'product_id',
        'seller_id',
        'affiliate_link_id',
        'amount',
        'platform_fee',
        'seller_earnings',
        'affiliate_earnings',
        'status',
        'payment_provider',
        'payment_ref',
        'download_token',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'amount'             => 'integer',
            'platform_fee'       => 'integer',
            'seller_earnings'    => 'integer',
            'affiliate_earnings' => 'integer',
            'status'             => OrderStatus::class,
            'expires_at'         => 'datetime',
        ];
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function affiliateLink(): BelongsTo
    {
        return $this->belongsTo(AffiliateLink::class);
    }

    public function affiliateSale(): HasOne
    {
        return $this->hasOne(AffiliateSale::class);
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopePaid($query)
    {
        return $query->where('status', OrderStatus::Paid->value);
    }

    public function scopePending($query)
    {
        return $query->where('status', OrderStatus::Pending->value);
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', OrderStatus::Refunded->value);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function isPaid(): bool
    {
        return $this->status === OrderStatus::Paid;
    }

    public function isExpired(): bool
    {
        return $this->expires_at !== null && $this->expires_at->isPast();
    }
}
