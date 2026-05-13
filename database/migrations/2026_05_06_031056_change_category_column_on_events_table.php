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
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE events MODIFY category VARCHAR(255) DEFAULT 'Umum'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE events MODIFY category ENUM('Seminar', 'Koordinasi', 'Workshop', 'Kompetisi', 'Umum') DEFAULT 'Umum'");
    }
};
