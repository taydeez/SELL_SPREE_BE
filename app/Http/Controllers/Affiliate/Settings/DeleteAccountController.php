<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Settings;

use App\Domain\Affiliate\Actions\DeleteAffiliateAccountAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Affiliate;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DeleteAccountController extends Controller
{
    public function __construct(private readonly DeleteAffiliateAccountAction $action) {}

    public function __invoke(): JsonResponse
    {
        $user      = Auth::guard('affiliate')->user();
        $affiliate = Affiliate::where('user_id', $user->id)->firstOrFail();

        Auth::guard('affiliate')->logout();

        $this->action->run($user, $affiliate);

        return ApiResponse::noContent();
    }
}
