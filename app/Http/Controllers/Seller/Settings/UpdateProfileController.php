<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Settings;

use App\Domain\Seller\Actions\UpdateSellerProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\UpdateSellerProfileRequest;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\Seller\SellerProfileResource;
use App\Models\Seller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UpdateProfileController extends Controller
{
    public function __construct(private readonly UpdateSellerProfileAction $action) {}

    public function __invoke(UpdateSellerProfileRequest $request): JsonResponse
    {
        $user   = Auth::guard('seller')->user();
        $seller = Seller::where('user_id', $user->id)->firstOrFail();

        $updated = $this->action->run(
            $user,
            $seller,
            $request->validated(),
            $request->file('avatar'),
        );

        return ApiResponse::success(new SellerProfileResource($updated->load('user')));
    }
}
