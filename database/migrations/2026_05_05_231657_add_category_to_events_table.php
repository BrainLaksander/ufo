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
        Schema::table('events', function (Blueprint $table) {
            $table->enum('category', [
                'Seminar', 
                'Koordinasi', 
                'Workshop', 
                'Kompetisi', 
                'Umum'
            ])->default('Umum')->after('title');
            
            $table->index(['start_at', 'category']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropIndex(['start_at', 'category']);
            $table->dropColumn('category');
        });
    }
};
