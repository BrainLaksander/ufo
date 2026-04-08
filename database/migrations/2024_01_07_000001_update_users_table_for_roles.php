<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Update Users table
 * Menambahkan role dan organization_id untuk role-based access
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Role column already created in create_users_table.php, skip re-adding
            // $table->enum('role', ['admin', 'pengurus', 'mahasiswa'])->default('mahasiswa')->after('email');
            
            if (!Schema::hasColumn('users', 'organization_id')) {
                $table->unsignedBigInteger('organization_id')->nullable()->after('role');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('organization_id');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('avatar');
            }

            if (Schema::hasTable('organizations') && !Schema::hasColumn('users', 'foreign_organization_id')) {
                $table->foreign('organization_id')->references('id')->on('organizations')->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['organization_id']);
            $table->dropColumn(['role', 'organization_id', 'phone', 'avatar', 'last_login_at']);
        });
    }
};
