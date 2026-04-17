<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Admin;

use App\Const\Auth\AuthMessages;
use App\Domain\Auth\UseCases\LoginAdminUseCase;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\Admin\AdminResource;
use App\Http\Resources\ApiResponse;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function __construct(private readonly LoginAdminUseCase $useCase) {}

    public function __invoke(LoginRequest $request): JsonResponse
    {
        $result = $this->useCase->run($request->email, $request->password);

        $result['user'] = new AdminResource($result['user']);

        return ApiResponse::success($result, AuthMessages::LOGIN_SUCCESS);
    }
}
