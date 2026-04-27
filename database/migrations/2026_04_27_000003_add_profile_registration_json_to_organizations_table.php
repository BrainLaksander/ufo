<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('organizations')) {
            return;
        }

        Schema::table('organizations', function (Blueprint $table) {
            if (!Schema::hasColumn('organizations', 'profile_registration_json')) {
                $table->longText('profile_registration_json')->nullable()->after('profile_contacts_json');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('organizations')) {
            return;
        }

        Schema::table('organizations', function (Blueprint $table) {
            if (Schema::hasColumn('organizations', 'profile_registration_json')) {
                $table->dropColumn('profile_registration_json');
            }
        });
    }
};