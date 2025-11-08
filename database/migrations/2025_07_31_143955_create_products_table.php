<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->decimal('original_price', 10, 2)->nullable();
            $table->integer('discount')->nullable();
            $table->string('image');
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->string('brand', 100);
            $table->string('sku', 50)->unique();
            $table->integer('stock_quantity')->default(0);
            $table->boolean('in_stock')->default(true);
            $table->integer('rating')->default(0);
            $table->integer('review_count')->default(0);
            $table->json('specs')->nullable();
            $table->json('colors')->nullable();
            $table->json('storage_options')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Indexes for better query performance
            $table->index('category_id');
            $table->index('brand');
            $table->index('price');
            $table->index('in_stock');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
