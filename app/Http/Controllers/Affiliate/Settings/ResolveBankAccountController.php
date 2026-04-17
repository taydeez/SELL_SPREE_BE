<?php

declare(strict_types=1);

namespace App\Http\Controllers\Affiliate\Settings;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Services\FlutterwaveService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ResolveBankAccountController extends Controller
{
    public function __construct(private readonly FlutterwaveService $flutterwave) {}

    public function __invoke(Request $request): JsonResponse
    {
        $request->validate([
            'account_number' => ['required', 'string', 'digits:10'],
            'bank_code'      => ['required', 'string'],
        ]);

        $result = $this->flutterwave->verifyBankAccount([
            'account_number' => $request->input('account_number'),
            'account_bank'   => $request->input('bank_code'),
        ]);

        if (($result['status'] ?? '') !== 'success' || empty($result['data']['account_name'])) {
            throw ValidationException::withMessages([
                'account_number' => ['Could not verify this account. Please check your details.'],
            ]);
        }

        return ApiResponse::success([
            'account_name'   => $result['data']['account_name'],
            'account_number' => $result['data']['account_number'],
        ]);
    }
}
