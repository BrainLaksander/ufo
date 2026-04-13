<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kemahasiswaan_ukm_accounts') || Schema::hasColumn('kemahasiswaan_ukm_accounts', 'password_hash')) {
            return;
        }

        Schema::table('kemahasiswaan_ukm_accounts', function (Blueprint $table) {
            $table->string('password_hash')->nullable()->after('email');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('kemahasiswaan_ukm_accounts') || !Schema::hasColumn('kemahasiswaan_ukm_accounts', 'password_hash')) {
            return;
        }

        Schema::table('kemahasiswaan_ukm_accounts', function (Blueprint $table) {
            $table->dropColumn('password_hash');
        });
    }
};
