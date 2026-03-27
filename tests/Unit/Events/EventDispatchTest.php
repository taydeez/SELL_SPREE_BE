<?php

declare(strict_types=1);

use App\Domain\Admin\Actions\CreateAdminAction;
use App\Domain\Affiliate\UseCases\RegisterAffiliateUseCase;
use App\Domain\Seller\UseCases\RegisterSellerUseCase;
use App\Events\Admin\AdminCreated;
use App\Events\Affiliate\AffiliateRegistered;
use App\Events\Seller\SellerRegistered;
use App\Listeners\Admin\HandleAdminCreated;
use App\Listeners\Affiliate\HandleAffiliateRegistered;
use App\Listeners\Seller\HandleSellerRegistered;
use App\Providers\EventServiceProvider;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Notification;

// ─── SellerRegistered dispatched from RegisterSellerUseCase ──────────────

it('seller registered event is dispatched on registration', function () {
    Notification::fake();
    Event::fake([SellerRegistered::class]);

    /** @var RegisterSellerUseCase $useCase */
    $useCase = app(RegisterSellerUseCase::class);

    $useCase->run([
        'name'       => 'Jane Seller',
        'email'      => 'jane@example.com',
        'password'   => 'password123',
        'store_name' => 'Jane\'s Store',
    ]);

    Event::assertDispatched(SellerRegistered::class);
});

it('seller registered event carries correct user and seller', function () {
    Notification::fake();
    Event::fake([SellerRegistered::class]);

    /** @var RegisterSellerUseCase $useCase */
    $useCase = app(RegisterSellerUseCase::class);

    $useCase->run([
        'name'       => 'Bob Seller',
        'email'      => 'bob@example.com',
        'password'   => 'password123',
        'store_name' => 'Bob\'s Shop',
    ]);

    Event::assertDispatched(SellerRegistered::class, function (SellerRegistered $event): bool {
        return $event->user->email === 'bob@example.com'
            && $event->seller->store_name === 'Bob\'s Shop';
    });
});

// ─── AffiliateRegistered dispatched from RegisterAffiliateUseCase ─────────

it('affiliate registered event is dispatched on registration', function () {
    Notification::fake();
    Event::fake([AffiliateRegistered::class]);

    /** @var RegisterAffiliateUseCase $useCase */
    $useCase = app(RegisterAffiliateUseCase::class);

    $useCase->run([
        'name'     => 'Alice Affiliate',
        'email'    => 'alice@example.com',
        'password' => 'password123',
    ]);

    Event::assertDispatched(AffiliateRegistered::class);
});

it('affiliate registered event carries correct user and affiliate', function () {
    Notification::fake();
    Event::fake([AffiliateRegistered::class]);

    /** @var RegisterAffiliateUseCase $useCase */
    $useCase = app(RegisterAffiliateUseCase::class);

    $useCase->run([
        'name'         => 'Charlie Aff',
        'email'        => 'charlie@example.com',
        'password'     => 'password123',
        'display_name' => 'CharliePromo',
    ]);

    Event::assertDispatched(AffiliateRegistered::class, function (AffiliateRegistered $event): bool {
        return $event->user->email === 'charlie@example.com'
            && $event->affiliate->display_name === 'CharliePromo';
    });
});

// ─── AdminCreated dispatched from CreateAdminAction ───────────────────────

it('admin created event is dispatched when creating admin', function () {
    Event::fake([AdminCreated::class]);

    /** @var CreateAdminAction $action */
    $action = app(CreateAdminAction::class);

    $action->run([
        'name'     => 'Admin User',
        'email'    => 'admin@example.com',
        'password' => 'password123',
    ]);

    Event::assertDispatched(AdminCreated::class);
});

it('admin created event carries the created admin user', function () {
    Event::fake([AdminCreated::class]);

    /** @var CreateAdminAction $action */
    $action = app(CreateAdminAction::class);

    $action->run([
        'name'     => 'Super Admin',
        'email'    => 'superadmin@example.com',
        'password' => 'password123',
    ]);

    Event::assertDispatched(AdminCreated::class, function (AdminCreated $event): bool {
        return $event->admin->email === 'superadmin@example.com';
    });
});

// ─── Listeners are registered in EventServiceProvider ────────────────────

it('seller registered listener is registered in event service provider', function () {
    $provider = new EventServiceProvider($this->app);
    $listen   = $provider->listens();

    $this->assertArrayHasKey(SellerRegistered::class, $listen);
    $this->assertContains(HandleSellerRegistered::class, $listen[SellerRegistered::class]);
});

it('affiliate registered listener is registered in event service provider', function () {
    $provider = new EventServiceProvider($this->app);
    $listen   = $provider->listens();

    $this->assertArrayHasKey(AffiliateRegistered::class, $listen);
    $this->assertContains(HandleAffiliateRegistered::class, $listen[AffiliateRegistered::class]);
});

it('admin created listener is registered in event service provider', function () {
    $provider = new EventServiceProvider($this->app);
    $listen   = $provider->listens();

    $this->assertArrayHasKey(AdminCreated::class, $listen);
    $this->assertContains(HandleAdminCreated::class, $listen[AdminCreated::class]);
});

// ─── Listeners implement ShouldQueue ──────────────────────────────────────

it('handle seller registered listener implements should queue', function () {
    $listener = new HandleSellerRegistered();

    expect($listener)->toBeInstanceOf(ShouldQueue::class);
});

it('handle affiliate registered listener implements should queue', function () {
    $listener = new HandleAffiliateRegistered();

    expect($listener)->toBeInstanceOf(ShouldQueue::class);
});

it('handle admin created listener implements should queue', function () {
    $listener = new HandleAdminCreated();

    expect($listener)->toBeInstanceOf(ShouldQueue::class);
});

// ─── Events dispatched only once ─────────────────────────────────────────

it('seller registered event is dispatched exactly once per registration', function () {
    Notification::fake();
    Event::fake([SellerRegistered::class]);

    /** @var RegisterSellerUseCase $useCase */
    $useCase = app(RegisterSellerUseCase::class);

    $useCase->run([
        'name'       => 'One Time Seller',
        'email'      => 'once@example.com',
        'password'   => 'password123',
        'store_name' => 'Once Store',
    ]);

    Event::assertDispatchedTimes(SellerRegistered::class, 1);
});

it('affiliate registered event is dispatched exactly once per registration', function () {
    Notification::fake();
    Event::fake([AffiliateRegistered::class]);

    /** @var RegisterAffiliateUseCase $useCase */
    $useCase = app(RegisterAffiliateUseCase::class);

    $useCase->run([
        'name'     => 'One Time Affiliate',
        'email'    => 'once-aff@example.com',
        'password' => 'password123',
    ]);

    Event::assertDispatchedTimes(AffiliateRegistered::class, 1);
});

it('admin created event is dispatched exactly once', function () {
    Event::fake([AdminCreated::class]);

    /** @var CreateAdminAction $action */
    $action = app(CreateAdminAction::class);

    $action->run([
        'name'     => 'Admin Once',
        'email'    => 'admin-once@example.com',
        'password' => 'password123',
    ]);

    Event::assertDispatchedTimes(AdminCreated::class, 1);
});
