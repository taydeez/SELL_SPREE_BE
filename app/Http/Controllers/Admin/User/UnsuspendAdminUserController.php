<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\User;

use App\Domain\Admin\UseCases\User\UnsuspendAdminUserUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminResource;
use App\Http\Resources\ApiResponse;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;

class UnsuspendAdminUserController extends Controller
{
    public function __construct(private UnsuspendAdminUserUseCase $useCase) {}

    public function __invoke(Admin $admin): JsonResponse
    {
        $this->useCase->run($admin);
        return ApiResponse::success(new AdminResource($admin->refresh()));
    }
}
