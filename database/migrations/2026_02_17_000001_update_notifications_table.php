<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            if (!Schema::hasColumn('notifications', 'type')) {
                $table->string('type')->nullable()->after('body');
            }
            // Change title/body to text to ensure they can hold JSON if needed, 
            // though string/text is usually enough for titles.
            $table->text('title')->change();
            $table->text('body')->change();
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
};
