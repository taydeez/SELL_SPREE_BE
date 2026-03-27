<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentConfig extends Model
{
    use HasFactory, HasUlid;

    protected $fillable = [
        'provider',
        'public_key',
        'secret_key',
        'webhook_secret',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'public_key'     => 'encrypted',
            'secret_key'     => 'encrypted',
            'webhook_secret' => 'encrypted',
            'is_active'      => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
