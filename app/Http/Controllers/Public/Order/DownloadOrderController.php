<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public\Order;

use App\Domain\Order\Actions\DownloadOrderAction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class DownloadOrderController extends Controller
{
    public function __construct(private readonly DownloadOrderAction $action) {}

    public function __invoke(string $token): RedirectResponse|JsonResponse
    {
        $signedUrl = $this->action->run($token);

        return redirect()->away($signedUrl);
    }
}
