<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AffiliateWithdrawal extends Model
{
    use HasFactory, HasUlid;

    protected $fillable = [
        'affiliate_id',
        'amount',
        'status',
        'bank_code',
        'bank_name',
        'account_number',
        'account_name',
        'flw_transfer_id',
        'flw_reference',
        'failure_reason',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
        ];
    }

    public function affiliate(): BelongsTo
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }
}
