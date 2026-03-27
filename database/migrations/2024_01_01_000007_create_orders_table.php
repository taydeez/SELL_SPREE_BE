<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('buyer_email');
            $table->string('product_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->string('seller_id');
            $table->foreign('seller_id')->references('id')->on('sellers');
            $table->string('affiliate_link_id')->nullable();
            $table->foreign('affiliate_link_id')->references('id')->on('affiliate_links')->nullOnDelete();
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('platform_fee');
            $table->unsignedBigInteger('seller_earnings');
            $table->unsignedBigInteger('affiliate_earnings')->default(0);
            $table->string('status')->default('pending');
            $table->string('payment_provider')->nullable();
            $table->string('payment_ref')->nullable();
            $table->string('download_token')->nullable()->unique();
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
