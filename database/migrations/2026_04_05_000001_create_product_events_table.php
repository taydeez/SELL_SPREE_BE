<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_events', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('product_id');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unique('product_id');
            $table->enum('event_type', ['online', 'physical']);
            $table->datetime('event_date');
            $table->datetime('event_end_date')->nullable();
            $table->string('timezone', 50)->default('UTC');
            $table->string('venue_name')->nullable();
            $table->text('venue_address')->nullable();
            $table->string('stream_url', 500)->nullable();
            $table->text('access_instructions')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_events');
    }
};
