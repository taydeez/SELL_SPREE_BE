<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Settings;

use App\Domain\Seller\UseCases\Settings\SaveBankAccountUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seller\SaveBankAccountRequest;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SaveBankAccountController extends Controller
{
    public function __construct(private readonly SaveBankAccountUseCase $useCase) {}

    public function __invoke(SaveBankAccountRequest $request): JsonResponse
    {
        return ApiResponse::success(
            $this->useCase->run(Auth::guard('seller')->user(), $request->validated()),
            'Bank account saved successfully.',
        );
    }
}
