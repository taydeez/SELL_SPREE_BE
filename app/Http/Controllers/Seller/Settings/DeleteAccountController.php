<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Settings;

use App\Domain\Seller\UseCases\Settings\DeleteAccountUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DeleteAccountController extends Controller
{
    public function __construct(private readonly DeleteAccountUseCase $useCase) {}

    public function __invoke(): JsonResponse
    {
        $this->useCase->run(Auth::guard('seller')->user());

        return ApiResponse::noContent();
    }
}
