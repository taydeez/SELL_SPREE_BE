<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_files', function (Blueprint $table) {
            $table->string('id', 26)->primary();
            $table->string('product_id', 26);
            $table->string('collection');           // 'cover' | 'product_file'
            $table->string('original_name');
            $table->string('file_name');            // sanitized, used in R2 path
            $table->string('path');                 // R2 object key
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('disk')->default('r2');
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('products')->cascadeOnDelete();
            $table->unique(['product_id', 'collection']); // one file per collection per product
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_files');
    }
};
