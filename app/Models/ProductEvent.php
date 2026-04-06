<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\EventType;
use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductEvent extends Model
{
    use HasFactory, HasUlid;

    protected $fillable = [
        'product_id',
        'event_type',
        'event_date',
        'event_end_date',
        'timezone',
        'venue_name',
        'venue_address',
        'stream_url',
        'access_instructions',
    ];

    protected function casts(): array
    {
        return [
            'event_type'     => EventType::class,
            'event_date'     => 'datetime',
            'event_end_date' => 'datetime',
        ];
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
