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
            $table->dropColumn(['secretary_name', 'treasurer_name']);
            
            $table->unsignedBigInteger('secretary_id')->nullable()->after('chair_email');
            $table->unsignedBigInteger('treasurer_id')->nullable()->after('secretary_email');
            
            $table->string('advisor_phone', 50)->nullable()->after('advisor_id');
            $table->string('advisor_email', 255)->nullable()->after('advisor_phone');

            $table->foreign('secretary_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('treasurer_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->dropForeign(['secretary_id']);
            $table->dropForeign(['treasurer_id']);
            
            $table->dropColumn(['secretary_id', 'treasurer_id', 'advisor_phone', 'advisor_email']);
            
            $table->string('secretary_name', 255)->nullable();
            $table->string('treasurer_name', 255)->nullable();
        });
    }
};
