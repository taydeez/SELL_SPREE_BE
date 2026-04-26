<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth\Social;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class FacebookRedirectController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json([
            'message' => 'Facebook login is not yet available.',
        ], 501);
    }
}
