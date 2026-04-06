<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('variant_id')->nullable()->after('product_id');
            $table->foreign('variant_id')->references('id')->on('product_variants')->nullOnDelete();
            $table->string('attendee_name')->nullable()->after('variant_id');
            $table->string('ticket_number', 32)->nullable()->unique()->after('attendee_name');
            $table->string('qr_code_path', 500)->nullable()->after('ticket_number');
            $table->timestamp('checked_in_at')->nullable()->after('qr_code_path');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropColumn(['variant_id', 'attendee_name', 'ticket_number', 'qr_code_path', 'checked_in_at']);
        });
    }
};
