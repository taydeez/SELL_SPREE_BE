<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Settings;

use App\Domain\Affiliate\UseCases\Settings\SaveBankAccountUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Affiliate\SaveBankAccountRequest;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SaveBankAccountController extends Controller
{
    public function __construct(private readonly SaveBankAccountUseCase $useCase) {}

    public function __invoke(SaveBankAccountRequest $request): JsonResponse
    {
        $affiliate = $this->useCase->run(Auth::guard('affiliate')->user(), $request->validated());

        return ApiResponse::success([
            'bank_name'      => $affiliate->bank_name,
            'account_name'   => $affiliate->account_name,
            'account_number' => $affiliate->account_number,
        ]);
    }
}
