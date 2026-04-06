<?php
/*
 * © 2026 Demilade Oyewusi
 * Licensed under the MIT License.
 * See the LICENSE file for details.
 */

namespace App\Http\Controllers\Payment\Flutterwave;

use App\Domain\Payment\Flutterwave\UseCases\FlutterwaveVerifyUseCase;
use App\Http\Requests\Payment\Flutterwave\FlutterwaveVerifyRequest;
use App\Http\Resources\ApiResponse;

class FlutterwaveVerifyController
{
    public function __construct(private FlutterwaveVerifyUseCase $useCase){}

            public function __invoke(FlutterwaveVerifyRequest $request){

                  $transactionVerificationResponse =  $this->useCase->run(data: $request->validated());

                  return ApiResponse::success($transactionVerificationResponse);
        }
}
