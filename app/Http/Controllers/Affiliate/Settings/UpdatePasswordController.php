<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Settings;

use App\Domain\Affiliate\Actions\UpdateAffiliatePasswordAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Affiliate\UpdatePasswordRequest;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UpdatePasswordController extends Controller
{
    public function __construct(private readonly UpdateAffiliatePasswordAction $action) {}

    public function __invoke(UpdatePasswordRequest $request): JsonResponse
    {
        $user = Auth::guard('affiliate')->user();

        $this->action->run($user, $request->current_password, $request->password);

        return ApiResponse::success(message: 'Password updated successfully.');
    }
}
