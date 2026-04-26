<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Role;

use App\Domain\Admin\UseCases\Role\CreateRoleUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateRoleRequest;
use App\Http\Resources\Admin\RoleResource;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class CreateRoleController extends Controller
{
    public function __construct(private CreateRoleUseCase $useCase) {}

    public function __invoke(CreateRoleRequest $request): JsonResponse
    {
        $role = $this->useCase->run($request->validated());
        return ApiResponse::created(new RoleResource($role));
    }
}
