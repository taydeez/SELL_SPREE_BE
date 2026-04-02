<?php

declare(strict_types=1);

use App\Http\Controllers\Public\Product\ShowPublicProductController;
use Illuminate\Support\Facades\Route;

Route::get('products/{slug}', ShowPublicProductController::class);
