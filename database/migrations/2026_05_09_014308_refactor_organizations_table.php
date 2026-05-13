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
            $table->dropColumn('type');
            $table->renameColumn('category', 'kategori');
            
            $table->unsignedBigInteger('ketua_id')->nullable()->after('kategori');
            $table->unsignedBigInteger('advisor_id')->nullable()->after('ketua_id');

            $table->foreign('ketua_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('advisor_id')->references('id')->on('users')->onDelete('set null');

            $table->dropColumn(['chair_name', 'advisor_name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('organizations', function (Blueprint $table) {
            $table->string('type')->nullable();
            $table->renameColumn('kategori', 'category');
            
            $table->string('chair_name')->nullable();
            $table->string('advisor_name')->nullable();

            $table->dropForeign(['ketua_id']);
            $table->dropForeign(['advisor_id']);
            $table->dropColumn(['ketua_id', 'advisor_id']);
        });
    }
};
