<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payouts', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('payable_type');
            $table->string('payable_id');
            $table->unsignedBigInteger('amount');
            $table->string('status')->default('pending');
            $table->string('provider')->nullable();
            $table->string('reference')->nullable()->unique();
            $table->timestamps();

            $table->index(['payable_type', 'payable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
