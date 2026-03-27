<?php

declare(strict_types=1);

use App\Enums\ProductType;

dataset('product_types', fn () => array_map(
    fn (ProductType $type) => [$type],
    ProductType::cases(),
));
