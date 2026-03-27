<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_configs', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('provider')->unique();
            $table->text('public_key');
            $table->text('secret_key');
            $table->text('webhook_secret')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_configs');
    }
};
