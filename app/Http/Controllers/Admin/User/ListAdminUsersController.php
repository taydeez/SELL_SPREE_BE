<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\User;

use App\Domain\Admin\UseCases\User\ListAdminUsersUseCase;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AdminResource;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class ListAdminUsersController extends Controller
{
    public function __construct(private ListAdminUsersUseCase $useCase) {}

    public function __invoke(): JsonResponse
    {
        $admins = $this->useCase->run();
        return ApiResponse::success(AdminResource::collection($admins));
    }
}
