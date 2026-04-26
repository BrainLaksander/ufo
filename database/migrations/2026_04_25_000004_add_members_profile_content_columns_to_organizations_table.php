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
            if (!Schema::hasColumn('organizations', 'profile_values_json')) {
                $table->longText('profile_values_json')->nullable()->after('field');
            }

            if (!Schema::hasColumn('organizations', 'profile_programs_json')) {
                $table->longText('profile_programs_json')->nullable()->after('profile_values_json');
            }

            if (!Schema::hasColumn('organizations', 'profile_structure_json')) {
                $table->longText('profile_structure_json')->nullable()->after('profile_programs_json');
            }

            if (!Schema::hasColumn('organizations', 'profile_contacts_json')) {
                $table->longText('profile_contacts_json')->nullable()->after('profile_structure_json');
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('organizations')) {
            return;
        }

        Schema::table('organizations', function (Blueprint $table) {
            $dropColumns = [];

            if (Schema::hasColumn('organizations', 'profile_contacts_json')) {
                $dropColumns[] = 'profile_contacts_json';
            }

            if (Schema::hasColumn('organizations', 'profile_structure_json')) {
                $dropColumns[] = 'profile_structure_json';
            }

            if (Schema::hasColumn('organizations', 'profile_programs_json')) {
                $dropColumns[] = 'profile_programs_json';
            }

            if (Schema::hasColumn('organizations', 'profile_values_json')) {
                $dropColumns[] = 'profile_values_json';
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
