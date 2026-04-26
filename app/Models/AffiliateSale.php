<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateSale extends Model
{
    use HasFactory, HasUlid;

    protected $fillable = [
        'affiliate_id',
        'affiliate_link_id',
        'order_id',
        'commission_amount',
        'is_paid',
        'status',
        'available_at',
        'settled_at',
    ];

    protected function casts(): array
    {
        return [
            'commission_amount' => 'integer',
            'is_paid'           => 'boolean',
            'available_at'      => 'datetime',
            'settled_at'        => 'datetime',
        ];
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeSettled($query)
    {
        return $query->where('status', 'settled');
    }

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function affiliateLink(): BelongsTo
    {
        return $this->belongsTo(AffiliateLink::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
