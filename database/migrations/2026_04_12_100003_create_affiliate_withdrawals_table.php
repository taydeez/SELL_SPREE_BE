<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliate_withdrawals', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('affiliate_id');
            $table->foreign('affiliate_id')->references('id')->on('affiliates');
            $table->unsignedBigInteger('amount');
            $table->string('status')->default('pending');
            // pending → processing → paid | failed
            $table->string('bank_code');
            $table->string('bank_name');
            $table->string('account_number');
            $table->string('account_name');
            $table->string('flw_transfer_id')->nullable();
            $table->string('flw_reference')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliate_withdrawals');
    }
};
