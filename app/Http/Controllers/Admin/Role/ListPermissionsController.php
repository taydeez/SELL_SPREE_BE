<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Role;

use App\Domain\Admin\UseCases\Role\ListPermissionsUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class ListPermissionsController extends Controller
{
    public function __construct(private ListPermissionsUseCase $useCase) {}

    public function __invoke(): JsonResponse
    {
        $permissions = $this->useCase->run()->pluck('name');
        return ApiResponse::success($permissions);
    }
}
