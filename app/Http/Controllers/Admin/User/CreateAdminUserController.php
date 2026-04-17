<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\User;

use App\Domain\Admin\UseCases\User\CreateAdminUserUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CreateAdminUserRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class CreateAdminUserController extends Controller
{
    public function __construct(private CreateAdminUserUseCase $useCase) {}

    public function __invoke(CreateAdminUserRequest $request): JsonResponse
    {
        $admin = $this->useCase->run($request->validated());
        return ApiResponse::created(new AdminResource($admin));
    }
}
