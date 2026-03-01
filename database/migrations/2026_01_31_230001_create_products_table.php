<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->id();
                $table->foreignId('category_id')->constrained('product_categories')->onDelete('cascade');
                $table->json('name'); // Translatable
                $table->json('description')->nullable(); // Translatable
                $table->decimal('price', 8, 2);
                $table->decimal('rate', 3, 2)->default(0.00);
                $table->string('image')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
