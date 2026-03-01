<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('slider_images_offers', function (Blueprint $table) {
            if (!Schema::hasColumn('slider_images_offers', 'title')) {
                $table->json('title')->after('id')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('slider_images_offers', function (Blueprint $table) {
            $table->dropColumn('title');
        });
    }
};
