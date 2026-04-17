<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Logs;

use App\Domain\Shared\Actions\ListAppLogsAction;
use App\Http\Resources\Admin\AppLogResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ListLogsController
{
    public function __invoke(Request $request, ListAppLogsAction $action): AnonymousResourceCollection
    {
        $logs = $action->run([
            'level'     => $request->query('level'),
            'date_from' => $request->query('date_from'),
            'date_to'   => $request->query('date_to'),
            'search'    => $request->query('search'),
        ]);

        return AppLogResource::collection($logs);
    }
}
