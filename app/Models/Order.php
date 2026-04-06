<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\OrderStatus;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

class Order extends Model
{
    use HasFactory, HasUlid;

    protected $fillable = [
        'buyer_email',
        'product_id',
        'variant_id',
        'seller_id',
        'affiliate_link_id',
        'attendee_name',
        'ticket_number',
        'qr_code_path',
        'checked_in_at',
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
            'checked_in_at'      => 'datetime',
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

    public function variant(): BelongsTo
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
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

    public function isCheckedIn(): bool
    {
        return $this->checked_in_at !== null;
    }

    public function qrCodeUrl(): ?string
    {
        if (!$this->qr_code_path) {
            return null;
        }

        return Storage::disk('r2')->temporaryUrl($this->qr_code_path, now()->addHour());
    }
}
