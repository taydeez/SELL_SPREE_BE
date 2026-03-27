<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Settings;

use App\Domain\Seller\Actions\UpdateSellerPasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\UpdatePasswordRequest;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UpdatePasswordController extends Controller
{
    public function __construct(private readonly UpdateSellerPasswordAction $action) {}

    public function __invoke(UpdatePasswordRequest $request): JsonResponse
    {
        $user = Auth::guard('seller')->user();

        $this->action->run($user, $request->current_password, $request->password);

        return ApiResponse::success(message: 'Password updated successfully.');
    }
}
