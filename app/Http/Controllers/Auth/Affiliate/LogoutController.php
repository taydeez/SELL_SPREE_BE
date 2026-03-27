<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Affiliate;

use App\Const\Auth\AuthMessages;
use App\Domain\Auth\Actions\RevokeTokenAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class LogoutController extends Controller
{
    public function __construct(private readonly RevokeTokenAction $action) {}

    public function __invoke(): JsonResponse
    {
        $this->action->run('affiliate');

        return ApiResponse::success(message: AuthMessages::LOGOUT_SUCCESS);
    }
}
