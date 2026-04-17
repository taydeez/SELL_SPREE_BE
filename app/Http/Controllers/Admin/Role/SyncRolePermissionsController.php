<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Role;

use App\Domain\Admin\UseCases\Role\SyncRolePermissionsUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SyncRolePermissionsRequest;
use App\Http\Resources\Admin\RoleResource;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class SyncRolePermissionsController extends Controller
{
    public function __construct(private SyncRolePermissionsUseCase $useCase) {}

    public function __invoke(SyncRolePermissionsRequest $request, Role $role): JsonResponse
    {
        $this->useCase->run($role, $request->validated()['permissions']);
        $role->load('permissions');
        return ApiResponse::success(new RoleResource($role));
    }
}
