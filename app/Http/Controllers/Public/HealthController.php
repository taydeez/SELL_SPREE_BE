<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $checks = [
            'database' => $this->checkDatabase(),
            'cache'    => $this->checkCache(),
        ];

        $healthy = collect($checks)->every(fn ($c) => $c['status'] === 'ok');

        return response()->json([
            'status'  => $healthy ? 'ok' : 'degraded',
            'checks'  => $checks,
            'version' => config('app.version', '1.0.0'),
            'env'     => config('app.env'),
        ], $healthy ? 200 : 503);
    }

    private function checkDatabase(): array
    {
        try {
            DB::selectOne('SELECT 1');
            return ['status' => 'ok'];
        } catch (\Throwable $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    private function checkCache(): array
    {
        try {
            Cache::put('_health', 1, 5);
            Cache::get('_health');
            return ['status' => 'ok'];
        } catch (\Throwable $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}
