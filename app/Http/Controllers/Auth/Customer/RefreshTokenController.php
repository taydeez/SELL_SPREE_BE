<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Customer;

use App\Domain\Auth\Actions\RefreshTokenAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class RefreshTokenController extends Controller
{
    public function __construct(private readonly RefreshTokenAction $action) {}

    public function __invoke(): JsonResponse
    {
        return ApiResponse::success($this->action->run('customer'));
    }
}
