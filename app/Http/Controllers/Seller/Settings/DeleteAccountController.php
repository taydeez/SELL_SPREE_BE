<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Settings;

use App\Domain\Seller\Actions\DeleteSellerAccountAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DeleteAccountController extends Controller
{
    public function __construct(private readonly DeleteSellerAccountAction $action) {}

    public function __invoke(): JsonResponse
    {
        $user   = Auth::guard('seller')->user();
        $seller = Seller::where('user_id', $user->id)->firstOrFail();

        Auth::guard('seller')->logout();

        $this->action->run($user, $seller);

        return ApiResponse::noContent();
    }
}
