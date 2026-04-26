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
            if (!Schema::hasColumn('organizations', 'logo')) {
                $table->string('logo')->nullable()->after('mission');
            }

            if (!Schema::hasColumn('organizations', 'banner')) {
                $table->string('banner')->nullable()->after('logo');
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

            if (Schema::hasColumn('organizations', 'banner')) {
                $dropColumns[] = 'banner';
            }

            if (Schema::hasColumn('organizations', 'logo')) {
                $dropColumns[] = 'logo';
            }

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
