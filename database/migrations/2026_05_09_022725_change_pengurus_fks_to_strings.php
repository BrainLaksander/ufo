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
            // Drop foreign keys
            $table->dropForeign(['ketua_id']);
            $table->dropForeign(['secretary_id']);
            $table->dropForeign(['treasurer_id']);
            $table->dropForeign(['advisor_id']);

            // Drop id columns
            $table->dropColumn(['ketua_id', 'secretary_id', 'treasurer_id', 'advisor_id']);

            // Add name columns
            $table->string('ketua_name', 255)->nullable()->after('status');
            $table->string('secretary_name', 255)->nullable()->after('chair_email');
            $table->string('treasurer_name', 255)->nullable()->after('secretary_email');
            $table->string('advisor_name', 255)->nullable()->after('treasurer_email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropColumn(['ketua_name', 'secretary_name', 'treasurer_name', 'advisor_name']);

            $table->unsignedBigInteger('ketua_id')->nullable();
            $table->unsignedBigInteger('secretary_id')->nullable();
            $table->unsignedBigInteger('treasurer_id')->nullable();
            $table->unsignedBigInteger('advisor_id')->nullable();

            $table->foreign('ketua_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('secretary_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('treasurer_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('advisor_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};
