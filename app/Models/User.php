<?php

declare(strict_types=1);

namespace App\Models;

use App\Traits\HasUlid;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasUlid, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'social_avatar',
        'active_role',
        'roles',
        'email_verified_at',
        'is_suspended',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_suspended'      => 'boolean',
            'roles'             => 'array',
        ];
    }

    // ─── JWTSubject ──────────────────────────────────────────────────────────

    public function getJWTIdentifier(): mixed
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [
            'active_role' => $this->active_role,
        ];
    }

    // ─── Relations ───────────────────────────────────────────────────────────

    public function seller(): HasOne
    {
        return $this->hasOne(Seller::class);
    }

    public function affiliate(): HasOne
    {
        return $this->hasOne(Affiliate::class);
    }

    // ─── Role helpers ────────────────────────────────────────────────────────

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles ?? []);
    }

    public function hasAnyRole(array $roles): bool
    {
        return count(array_intersect($roles, $this->roles ?? [])) > 0;
    }

    public function addRole(string $role): void
    {
        $current = $this->roles ?? [];
        if (!in_array($role, $current)) {
            $this->update(['roles' => [...$current, $role]]);
        }
    }

    // ─── Scopes ──────────────────────────────────────────────────────────────

    public function scopeSuspended($query)
    {
        return $query->where('is_suspended', true);
    }

    public function scopeVerified($query)
    {
        return $query->whereNotNull('email_verified_at');
    }

    public function scopeByRole($query, string $role)
    {
        return $query->whereJsonContains('roles', $role);
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function isSeller(): bool
    {
        return $this->hasRole('seller');
    }

    public function isAffiliate(): bool
    {
        return $this->hasRole('affiliate');
    }
}
