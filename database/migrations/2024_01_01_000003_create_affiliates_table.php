<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('affiliates', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('user_id', 26);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('display_name');
            $table->string('slug')->unique();
            $table->string('payout_email')->nullable();
            $table->unsignedTinyInteger('commission_rate')->default(10);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affiliates');
    }
};
