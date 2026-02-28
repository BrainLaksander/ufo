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
            $table->enum('role', ['admin', 'pengurus', 'mahasiswa'])->default('mahasiswa')->after('email');
            $table->unsignedBigInteger('organization_id')->nullable()->after('role');
            $table->string('phone')->nullable()->after('organization_id');
            $table->string('avatar')->nullable()->after('phone');
            $table->timestamp('last_login_at')->nullable()->after('avatar');

            $table->foreign('organization_id')->references('id')->on('organizations')->nullOnDelete();
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
