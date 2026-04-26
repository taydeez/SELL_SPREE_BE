<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('affiliate_sales', function (Blueprint $table) {
            $table->string('status')->default('pending')->after('commission_amount');
            // pending → available → settled
            $table->timestamp('available_at')->nullable()->after('status');
            $table->timestamp('settled_at')->nullable()->after('available_at');
        });

        // Migrate existing data: paid records become settled, unpaid stay pending
        DB::table('affiliate_sales')->where('is_paid', true)->update([
            'status'       => 'settled',
            'available_at' => DB::raw('created_at'),
            'settled_at'   => DB::raw('updated_at'),
        ]);

        DB::table('affiliate_sales')->where('is_paid', false)->update([
            'status'       => 'pending',
            'available_at' => DB::raw("created_at + interval '7 days'"),
        ]);
    }

    public function down(): void
    {
        Schema::table('affiliate_sales', function (Blueprint $table) {
            $table->dropColumn(['status', 'available_at', 'settled_at']);
        });
    }
};
