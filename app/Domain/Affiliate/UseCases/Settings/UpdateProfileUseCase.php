<?php

declare(strict_types=1);

namespace App\Domain\Affiliate\UseCases\Settings;

use App\Domain\Affiliate\Actions\ResolveCurrentAffiliateAction;
use App\Domain\Affiliate\Actions\UpdateAffiliateProfileAction;
use App\Http\Resources\Affiliate\AffiliateProfileResource;
use App\Models\User;
use Illuminate\Http\UploadedFile;

class UpdateProfileUseCase
{
    public function __construct(
        private readonly ResolveCurrentAffiliateAction $resolveAffiliate,
        private readonly UpdateAffiliateProfileAction $updateProfile,
    ) {}

    public function run(User $user, array $data, ?UploadedFile $avatar): AffiliateProfileResource
    {
        $affiliate = $this->resolveAffiliate->run($user->id);

        return new AffiliateProfileResource(
            $this->updateProfile->run($user, $affiliate, $data, $avatar)->load('user')
        );
    }
}
