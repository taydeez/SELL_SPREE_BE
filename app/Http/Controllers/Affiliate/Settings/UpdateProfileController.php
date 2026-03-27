<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Settings;

use App\Domain\Affiliate\Actions\UpdateAffiliateProfileAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Affiliate\UpdateAffiliateProfileRequest;
use App\Http\Resources\Affiliate\AffiliateProfileResource;
use App\Http\Resources\ApiResponse;
use App\Models\Affiliate;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UpdateProfileController extends Controller
{
    public function __construct(private readonly UpdateAffiliateProfileAction $action) {}

    public function __invoke(UpdateAffiliateProfileRequest $request): JsonResponse
    {
        $user      = Auth::guard('affiliate')->user();
        $affiliate = Affiliate::where('user_id', $user->id)->firstOrFail();

        $updated = $this->action->run(
            $user,
            $affiliate,
            $request->validated(),
            $request->file('avatar'),
        );

        return ApiResponse::success(new AffiliateProfileResource($updated->load('user')));
    }
}
