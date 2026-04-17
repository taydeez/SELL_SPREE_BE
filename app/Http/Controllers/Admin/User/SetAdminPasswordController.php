<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\User;

use App\Domain\Admin\UseCases\User\SetAdminPasswordUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SetAdminPasswordRequest;
use App\Http\Resources\ApiResponse;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;

class SetAdminPasswordController extends Controller
{
    public function __construct(private SetAdminPasswordUseCase $useCase) {}

    public function __invoke(SetAdminPasswordRequest $request, Admin $admin): JsonResponse
    {
        $this->useCase->run($admin, $request->validated()['password']);
        return ApiResponse::success(['message' => 'Password updated.']);
    }
}
