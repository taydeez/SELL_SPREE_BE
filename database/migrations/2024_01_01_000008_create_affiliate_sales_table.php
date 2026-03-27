<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_sales', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('affiliate_id');
            $table->foreign('affiliate_id')->references('id')->on('affiliates');
            $table->string('affiliate_link_id');
            $table->foreign('affiliate_link_id')->references('id')->on('affiliate_links');
            $table->string('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->unsignedBigInteger('commission_amount');
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_sales');
    }
};
