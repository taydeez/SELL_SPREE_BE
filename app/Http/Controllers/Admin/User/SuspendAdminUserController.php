<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\User;

use App\Domain\Admin\UseCases\User\SuspendAdminUserUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminResource;
use App\Http\Resources\ApiResponse;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;

class SuspendAdminUserController extends Controller
{
    public function __construct(private SuspendAdminUserUseCase $useCase) {}

    public function __invoke(Admin $admin): JsonResponse
    {
        $this->useCase->run($admin);
        return ApiResponse::success(new AdminResource($admin->refresh()));
    }
}
