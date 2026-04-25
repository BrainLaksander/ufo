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
            if (!Schema::hasColumn('organizations', 'category')) {
                $table->string('category', 80)->nullable()->after('shortname');
            }

            if (!Schema::hasColumn('organizations', 'type')) {
                $table->string('type', 20)->nullable()->after('category');
            }

            if (!Schema::hasColumn('organizations', 'level')) {
                $table->string('level', 40)->nullable()->after('type');
            }

            if (!Schema::hasColumn('organizations', 'field')) {
                $table->string('field', 120)->nullable()->after('level');
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

            if (Schema::hasColumn('organizations', 'field')) {
                $dropColumns[] = 'field';
            }

            if (Schema::hasColumn('organizations', 'level')) {
                $dropColumns[] = 'level';
            }

            if (Schema::hasColumn('organizations', 'type')) {
                $dropColumns[] = 'type';
            }

            if (Schema::hasColumn('organizations', 'category')) {
                $dropColumns[] = 'category';
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
