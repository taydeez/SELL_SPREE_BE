<?php

declare(strict_types=1);

namespace App\Const\Auth;

class AuthMessages
{
    public const LOGIN_SUCCESS = 'Logged in successfully.';
    public const LOGOUT_SUCCESS = 'Logged out successfully.';
    public const REGISTER_SUCCESS = 'Account created successfully.';
    public const TOKEN_REFRESHED = 'Token refreshed successfully.';
    public const PASSWORD_RESET_REQUESTED = 'Password reset code sent to your email.';
    public const PASSWORD_RESET_VERIFIED = 'Reset code verified.';
    public const PASSWORD_RESET_CONFIRMED = 'Password reset successfully.';
    public const INVALID_CREDENTIALS = 'Invalid credentials.';
    public const ACCOUNT_SUSPENDED = 'Account has been suspended.';
    public const UNAUTHENTICATED = 'Unauthenticated.';
    public const UNAUTHORIZED = 'Unauthorized.';
}
