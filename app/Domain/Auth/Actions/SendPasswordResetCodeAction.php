<?php

declare(strict_types=1);

namespace App\Domain\Auth\Actions;

use App\Models\Admin;
use App\Models\PasswordResetCode;
use App\Models\User;
use App\Notifications\Auth\PasswordResetNotification;
use Illuminate\Database\Eloquent\Model;

class SendPasswordResetCodeAction
{
    public function run(string $email, string $guard): void
    {
        $user = $this->resolveUser($email, $guard);

        if (! $user) {
            return;
        }

        PasswordResetCode::where('email', $email)->where('guard', $guard)->delete();

        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        PasswordResetCode::create([
            'email'      => $email,
            'guard'      => $guard,
            'code'       => $code,
            'expires_at' => now()->addMinutes(15),
            'created_at' => now(),
        ]);

        $user->notify(new PasswordResetNotification($code, $guard));
    }

    private function resolveUser(string $email, string $guard): Model|null
    {
        if ($guard === 'admin') {
            return Admin::where('email', $email)->first();
        }

        return User::where('email', $email)->first();
    }
}
