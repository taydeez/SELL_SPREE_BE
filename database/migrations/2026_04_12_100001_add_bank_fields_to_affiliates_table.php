<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->string('bank_code')->nullable()->after('payout_email');
            $table->string('bank_name')->nullable()->after('bank_code');
            $table->string('account_number')->nullable()->after('bank_name');
            $table->string('account_name')->nullable()->after('account_number');
            $table->string('flw_recipient_id')->nullable()->after('account_name');
        });
    }

    public function down(): void
    {
        Schema::table('affiliates', function (Blueprint $table) {
            $table->dropColumn(['bank_code', 'bank_name', 'account_number', 'account_name', 'flw_recipient_id']);
        });
    }
};
