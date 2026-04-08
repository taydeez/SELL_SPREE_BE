<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Role;

use App\Domain\Admin\UseCases\Role\DeleteRoleUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class DeleteRoleController extends Controller
{
    public function __construct(private DeleteRoleUseCase $useCase) {}

    public function __invoke(Role $role): JsonResponse
    {
        $this->useCase->run($role);
        return ApiResponse::noContent();
    }
}
