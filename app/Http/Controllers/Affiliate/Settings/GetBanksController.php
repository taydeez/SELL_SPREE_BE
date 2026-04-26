<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Services\FlutterwaveService;
use Illuminate\Http\JsonResponse;

class GetBanksController extends Controller
{
    public function __construct(private readonly FlutterwaveService $flutterwave) {}

    public function __invoke(): JsonResponse
    {
        $result = $this->flutterwave->getBanks('NG');

        $banks = collect($result['data'] ?? [])->map(fn ($b) => [
            'code' => (string) $b['code'],
            'name' => $b['name'],
        ])->values()->all();

        return ApiResponse::success($banks);
    }
}
