<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->boolean('is_open_recruitment')->default(false);
            $table->string('recruitment_link')->nullable()->after('is_open_recruitment');
            $table->text('recruitment_req')->nullable()->after('recruitment_link');
        });
    }

    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['is_open_recruitment', 'recruitment_link', 'recruitment_req']);
        });
    }
};
