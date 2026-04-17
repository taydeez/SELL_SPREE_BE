<?php

declare(strict_types=1);

namespace App\Http\Controllers\Customer\Dashboard;

use App\Domain\Customer\Actions\GetCustomerStatsAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class GetStatsController extends Controller
{
    public function __construct(private readonly GetCustomerStatsAction $action) {}

    public function __invoke(): JsonResponse
    {
        $email = Auth::guard('customer')->user()->email;

        return ApiResponse::success($this->action->run($email));
    }
}
