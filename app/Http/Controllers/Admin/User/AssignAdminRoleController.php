<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\User;

use App\Domain\Admin\UseCases\User\AssignAdminRoleUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AssignAdminRoleRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Http\Resources\ApiResponse;
use App\Models\Admin;
use Illuminate\Http\JsonResponse;

class AssignAdminRoleController extends Controller
{
    public function __construct(private AssignAdminRoleUseCase $useCase) {}

    public function __invoke(AssignAdminRoleRequest $request, Admin $admin): JsonResponse
    {
        $this->useCase->run($admin, $request->validated()['role'] ?? null);
        return ApiResponse::success(new AdminResource($admin->refresh()->load('roles')));
    }
}
