<?php
/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */

namespace App\Http\Controllers\Payment\Flutterwave;


use App\Http\Requests\Payment\Flutterwave\FlutterwaveInitRequest;
use App\Http\Resources\ApiResponse;
use App\Domain\Payment\Flutterwave\UseCases\FlutterwaveInitUseCase;
use Illuminate\Http\JsonResponse;


class FlutterwaveInitController{

    public function __construct( private FlutterwaveInitUseCase $useCase){

    }

    public function __invoke(FlutterwaveInitRequest $request): JsonResponse
    {
        $flutterwaveInitData = $this->useCase->run(
            data : $request->validated()
    );

        return ApiResponse::success($flutterwaveInitData);

    }
}
