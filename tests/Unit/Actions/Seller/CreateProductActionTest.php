<?php

declare(strict_types=1);

use App\Domain\Seller\Actions\CreateProductAction;
use App\Enums\ProductStatus;
use App\Enums\ProductType;
use App\Models\Product;
use App\Models\Seller;

beforeEach(function () {
    $this->action = new CreateProductAction();
    $this->seller = Seller::factory()->approved()->create();
});

it('creates a product with the provided data', function () {
    $product = $this->action->run($this->seller, [
        'title'       => 'Test eBook',
        'description' => 'A helpful guide.',
        'type'        => ProductType::Ebook->value,
        'price'       => 250000,
    ]);

    expect($product)->toBeInstanceOf(Product::class);
    expect($product->title)->toBe('Test eBook');
    expect($product->description)->toBe('A helpful guide.');
    expect($product->price)->toBe(250000);
});

it('always sets status to draft', function () {
    $product = $this->action->run($this->seller, [
        'title' => 'Draft Product',
        'type'  => ProductType::Music->value,
        'price' => 100000,
    ]);

    expect($product->status)->toBe(ProductStatus::Draft);
});

it('sets seller id on the product', function () {
    $product = $this->action->run($this->seller, [
        'title' => 'Seller Product',
        'type'  => ProductType::Code->value,
        'price' => 500000,
    ]);

    expect($product->seller_id)->toBe($this->seller->id);
});

it('persists the product to the database', function () {
    $product = $this->action->run($this->seller, [
        'title' => 'Persisted Product',
        'type'  => ProductType::Video->value,
        'price' => 75000,
    ]);

    $this->assertDatabaseHas('products', [
        'id'        => $product->id,
        'title'     => 'Persisted Product',
        'seller_id' => $this->seller->id,
        'status'    => ProductStatus::Draft->value,
    ]);
});

it('accepts optional description as null', function () {
    $product = $this->action->run($this->seller, [
        'title' => 'No Description Product',
        'type'  => ProductType::Ticket->value,
        'price' => 50000,
    ]);

    expect($product->description)->toBeNull();
});

it('creates a product with the given type', function (App\Enums\ProductType $type) {
    $product = $this->action->run($this->seller, [
        'title' => "Product of type {$type->value}",
        'type'  => $type->value,
        'price' => 100000,
    ]);
    expect($product->type)->toBe($type);
})->with('product_types');

it('assigns a slug to the product', function () {
    $product = $this->action->run($this->seller, [
        'title' => 'Sluggable Product',
        'type'  => ProductType::Ebook->value,
        'price' => 100000,
    ]);

    expect($product->slug)->not->toBeEmpty();
    expect($product->slug)->toContain('sluggable-product');
});

it('returns a product model instance', function () {
    $result = $this->action->run($this->seller, [
        'title' => 'Return Type Check',
        'type'  => ProductType::Ebook->value,
        'price' => 100000,
    ]);

    expect($result)->toBeInstanceOf(Product::class);
    expect($result->exists)->toBeTrue();
});
