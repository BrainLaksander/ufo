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
        // 1. Events Table
        // Schema::table('events', function (Blueprint $table) {
        //     $table->boolean('is_unlimited_quota')->default(false)->after('participants');
        //     // assuming 'story' or similar doesn't exist, we just merge content in our controller.
        //     // if we need to remove 'status_awal', we would do it here, but I will check if it exists first.
        // });

        // 2. Announcements Table
        Schema::table('announcements', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::table('announcements')->where('status', 'terpublikasi')->update(['status' => 'sent']);
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE announcements MODIFY COLUMN status ENUM('draft', 'scheduled', 'sent') DEFAULT 'draft'");
        });

        // 3. Lost Items Table
        Schema::table('lost_items', function (Blueprint $table) {
            \Illuminate\Support\Facades\DB::statement("ALTER TABLE lost_items MODIFY COLUMN status ENUM('pending', 'active', 'resolved', 'rejected') DEFAULT 'pending'");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('is_unlimited_quota');
        });
        
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE announcements MODIFY COLUMN status VARCHAR(255) DEFAULT 'draft'");
        \Illuminate\Support\Facades\DB::statement("ALTER TABLE lost_items MODIFY COLUMN status ENUM('pending', 'active', 'resolved') DEFAULT 'pending'");
    }
};
