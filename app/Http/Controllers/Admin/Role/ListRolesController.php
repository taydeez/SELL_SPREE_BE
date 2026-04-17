<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Role;

use App\Domain\Admin\UseCases\Role\ListRolesUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\RoleResource;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class ListRolesController extends Controller
{
    public function __construct(private ListRolesUseCase $useCase) {}

    public function __invoke(): JsonResponse
    {
        $roles = $this->useCase->run();
        return ApiResponse::success(RoleResource::collection($roles));
    }
}
