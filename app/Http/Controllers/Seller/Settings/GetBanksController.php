<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Services\FlutterwaveService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetBanksController extends Controller
{
    public function __construct(private readonly FlutterwaveService $flutterwave) {}

    public function __invoke(Request $request): JsonResponse
    {
        $country = strtoupper($request->query('country', 'NG'));
        $result  = $this->flutterwave->getBanks($country);

        if (($result['status'] ?? '') !== 'success') {
            return ApiResponse::error('Could not fetch banks. Please try again.');
        }

        return ApiResponse::success($result['data']);
    }
}
