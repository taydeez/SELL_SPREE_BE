<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\PayoutStatus;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Payout extends Model
{
    use HasFactory, HasUlid;

    protected $fillable = [
        'payable_type',
        'payable_id',
        'amount',
        'status',
        'provider',
        'reference',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'status' => PayoutStatus::class,
        ];
    }

    public function payable(): MorphTo
    {
        return $this->morphTo();
    }
}
