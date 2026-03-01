<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'admin_role')) {
                // Roles: super_admin, accountant, data_entry
                $table->string('admin_role')->nullable()->after('is_admin');
            }
        });

        // Set existing admins to super_admin
        \App\Models\User::where('is_admin', true)->update(['admin_role' => 'super_admin']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('admin_role');
        });
    }
};
