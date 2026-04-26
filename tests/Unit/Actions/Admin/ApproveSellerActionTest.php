<?php

declare(strict_types=1);

use App\Domain\Admin\Actions\Seller\ApproveSellerAction;
use App\Exceptions\BusinessException;
use App\Models\Seller;

beforeEach(function () {
    $this->action = new ApproveSellerAction();
});

it('approves a pending seller', function () {
    $seller = Seller::factory()->create(['is_approved' => false]);

    $this->action->run($seller);

    expect($seller->fresh()->is_approved)->toBeTrue();
});

it('persists approval to the database', function () {
    $seller = Seller::factory()->create(['is_approved' => false]);

    $this->action->run($seller);

    $this->assertDatabaseHas('sellers', [
        'id'          => $seller->id,
        'is_approved' => true,
    ]);
});

it('throws business exception when seller is already approved', function () {
    $seller = Seller::factory()->approved()->create();

    $this->action->run($seller);
})->throws(BusinessException::class, 'Seller is already approved.');

it('throws exception with 422 status code for already approved', function () {
    $seller = Seller::factory()->approved()->create();

    try {
        $this->action->run($seller);
        $this->fail('Expected BusinessException was not thrown.');
    } catch (BusinessException $e) {
        expect($e->getStatusCode())->toBe(422);
    }
});

it('does not change already approved seller on exception', function () {
    $seller = Seller::factory()->approved()->create();

    try {
        $this->action->run($seller);
    } catch (BusinessException) {
        // Expected
    }

    // The seller should still be approved (no accidental mutation)
    expect($seller->fresh()->is_approved)->toBeTrue();
});
