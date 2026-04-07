<?php

declare(strict_types=1);

namespace App\Http\Controllers\Public\Ticket;

use App\Domain\Order\Actions\ValidateTicketAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse;
use App\Http\Resources\Public\TicketValidationResource;
use Illuminate\Http\JsonResponse;

class ValidateTicketController extends Controller
{
    public function __construct(private readonly ValidateTicketAction $validateTicketAction) {}

    public function __invoke(string $ticketNumber): JsonResponse
    {
        $result = $this->validateTicketAction->run($ticketNumber);

        return ApiResponse::success(new TicketValidationResource($result));
    }
}
