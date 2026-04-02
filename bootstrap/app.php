<?php

use App\Exceptions\BusinessException;
use App\Http\Middleware\ForceJsonResponse;
use App\Http\Resources\ApiResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware(['api', ForceJsonResponse::class, 'cache.response'])
                ->prefix('api/v1/admin')
                ->name('admin.')
                ->group(base_path('routes/api/admin.php'));

            Route::middleware(['api', ForceJsonResponse::class, 'cache.response'])
                ->prefix('api/v1/seller')
                ->name('seller.')
                ->group(base_path('routes/api/seller.php'));

            Route::middleware(['api', ForceJsonResponse::class, 'cache.response'])
                ->prefix('api/v1/affiliate')
                ->name('affiliate.')
                ->group(base_path('routes/api/affiliate.php'));

            Route::middleware(['api', ForceJsonResponse::class])
                ->prefix('api/v1/public')
                ->name('public.')
                ->group(base_path('routes/api/public.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth'           => \App\Http\Middleware\Authenticate::class,
            'admin'          => \App\Http\Middleware\AdminMiddleware::class,
            'seller'         => \App\Http\Middleware\SellerMiddleware::class,
            'affiliate'      => \App\Http\Middleware\AffiliateMiddleware::class,
            'cache.response' => \App\Http\Middleware\CacheResponseMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->render(function (NotFoundHttpException $e): JsonResponse {
            return ApiResponse::error('Resource not found.', status: JsonResponse::HTTP_NOT_FOUND);
        });

        $exceptions->render(function (AuthenticationException $e): JsonResponse {
            return ApiResponse::error('Unauthenticated.', status: JsonResponse::HTTP_UNAUTHORIZED);
        });

        $exceptions->render(function (ValidationException $e): JsonResponse {
            return ApiResponse::error(
                'Validation failed.',
                errors: $e->errors(),
                status: JsonResponse::HTTP_UNPROCESSABLE_ENTITY,
            );
        });

        $exceptions->render(function (BusinessException $e): JsonResponse {
            return ApiResponse::error(
                message: $e->getMessage(),
                errors: $e->getErrors(),
                status: $e->getStatusCode(),
            );
        });

    })->create();
