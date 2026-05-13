<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Item 11: Add revision_note to activity_submissions for revisi workflow
        if (!Schema::hasColumn('activity_submissions', 'revision_note')) {
            Schema::table('activity_submissions', function (Blueprint $table) {
                $table->text('revision_note')->nullable()->after('status');
            });
        }

        // Item 12: Add holiday/blocked fields to calendar_events
        if (!Schema::hasColumn('calendar_events', 'is_holiday')) {
            Schema::table('calendar_events', function (Blueprint $table) {
                $table->boolean('is_holiday')->default(false)->after('description');
                $table->boolean('extracurricular_blocked')->default(false)->after('is_holiday');
            });
        }
    }

    public function down(): void
    {
        Schema::table('activity_submissions', function (Blueprint $table) {
            $table->dropColumn('revision_note');
        });
        Schema::table('calendar_events', function (Blueprint $table) {
            $table->dropColumn(['is_holiday', 'extracurricular_blocked']);
        });
    }
};
