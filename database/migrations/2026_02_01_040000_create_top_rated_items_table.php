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
        if (!Schema::hasTable('top_rated_items')) {
            Schema::create('top_rated_items', function (Blueprint $table) {
                $table->id();
                $table->json('name'); // Translatable
                $table->string('image');
                $table->enum('type', ['Service', 'Product']);
                $table->decimal('rating', 3, 1);
                $table->decimal('price', 8, 2);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('top_rated_items');
    }
};
