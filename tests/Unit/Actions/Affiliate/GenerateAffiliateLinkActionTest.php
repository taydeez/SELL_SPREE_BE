<?php

declare(strict_types=1);

use App\Domain\Affiliate\Actions\GenerateAffiliateLinkAction;
use App\Exceptions\BusinessException;
use App\Models\Affiliate;
use App\Models\AffiliateLink;
use App\Models\Product;

beforeEach(function () {
    $this->action    = new GenerateAffiliateLinkAction();
    $this->affiliate = Affiliate::factory()->create();
});

it('creates a link for an active product', function () {
    $product = Product::factory()->active()->create();

    $link = $this->action->run($this->affiliate, $product);

    expect($link)->toBeInstanceOf(AffiliateLink::class);
    expect($link->affiliate_id)->toBe($this->affiliate->id);
    expect($link->product_id)->toBe($product->id);
    expect($link->is_active)->toBeTrue();
});

it('persists the link to the database', function () {
    $product = Product::factory()->active()->create();

    $link = $this->action->run($this->affiliate, $product);

    $this->assertDatabaseHas('affiliate_links', [
        'id'           => $link->id,
        'affiliate_id' => $this->affiliate->id,
        'product_id'   => $product->id,
        'is_active'    => true,
    ]);
});

it('returns existing link if one already exists for same affiliate and product', function () {
    $product  = Product::factory()->active()->create();
    $existing = AffiliateLink::factory()->for($this->affiliate)->create([
        'product_id' => $product->id,
        'is_active'  => true,
    ]);

    $result = $this->action->run($this->affiliate, $product);

    expect($result->id)->toBe($existing->id);
    $this->assertDatabaseCount('affiliate_links', 1);
});

it('throws business exception for a draft product', function () {
    $product = Product::factory()->create(); // Draft status

    $this->action->run($this->affiliate, $product);
})->throws(BusinessException::class, 'Links can only be created for active products.');

it('throws business exception for a paused product', function () {
    $product = Product::factory()->paused()->create();

    $this->action->run($this->affiliate, $product);
})->throws(BusinessException::class, 'Links can only be created for active products.');

it('throws exception with 422 status code for non active product', function () {
    $product = Product::factory()->create();

    try {
        $this->action->run($this->affiliate, $product);
        $this->fail('Expected BusinessException was not thrown.');
    } catch (BusinessException $e) {
        expect($e->getStatusCode())->toBe(422);
    }
});

it('generates a unique slug for each new link', function () {
    $productA = Product::factory()->active()->create();
    $productB = Product::factory()->active()->create();

    $linkA = $this->action->run($this->affiliate, $productA);
    $linkB = $this->action->run($this->affiliate, $productB);

    expect($linkA->slug)->not->toEqual($linkB->slug);
});

it('different affiliates can each have a link to the same product', function () {
    $product        = Product::factory()->active()->create();
    $otherAffiliate = Affiliate::factory()->create();

    $linkA = $this->action->run($this->affiliate, $product);
    $linkB = $this->action->run($otherAffiliate, $product);

    expect($linkA->id)->not->toEqual($linkB->id);
    $this->assertDatabaseCount('affiliate_links', 2);
});

it('does not create a new link when returning existing', function () {
    $product = Product::factory()->active()->create();

    $this->action->run($this->affiliate, $product);
    $this->action->run($this->affiliate, $product); // Second call

    $this->assertDatabaseCount('affiliate_links', 1);
});
