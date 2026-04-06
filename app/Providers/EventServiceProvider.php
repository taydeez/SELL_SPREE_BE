<?php

declare(strict_types=1);

namespace App\Providers;

use App\Domain\Order\Events\OrderPaidEvent;
use App\Events\Admin\AdminCreated;
use App\Events\Affiliate\AffiliateRegistered;
use App\Events\Seller\SellerRegistered;
use App\Listeners\Admin\HandleAdminCreated;
use App\Listeners\Affiliate\HandleAffiliateRegistered;
use App\Listeners\Order\HandleOrderPaidEvent;
use App\Listeners\Seller\HandleSellerRegistered;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * @var array<class-string, list<class-string>>
     */
    protected $listen = [
        AdminCreated::class => [
            HandleAdminCreated::class,
        ],

        SellerRegistered::class => [
            HandleSellerRegistered::class,
        ],

        AffiliateRegistered::class => [
            HandleAffiliateRegistered::class,
        ],

        OrderPaidEvent::class => [
            HandleOrderPaidEvent::class,
        ],
    ];
}
