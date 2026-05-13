<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('chair_photo')->nullable()->after('chair_email');
            $table->string('secretary_photo')->nullable()->after('secretary_email');
            $table->string('treasurer_photo')->nullable()->after('treasurer_email');
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['chair_photo', 'secretary_photo', 'treasurer_photo']);
        });
    }
};
