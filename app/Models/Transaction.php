<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasUlid;

    protected $fillable = [
        'tx_ref',
        'order_id',
        'transaction_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'amount',
        'currency',
        'provider',
        'status',
        'meta',
    ];

    protected $casts = [
        'amount' => 'integer',
        'meta'   => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
