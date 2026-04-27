<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('kemahasiswaan_announcements')) {
            return;
        }

        Schema::table('kemahasiswaan_announcements', function (Blueprint $table): void {
            if (!Schema::hasColumn('kemahasiswaan_announcements', 'recipient_mode')) {
                $table->enum('recipient_mode', ['all_students', 'manual'])
                    ->default('all_students')
                    ->after('target_audience');
            }

            if (!Schema::hasColumn('kemahasiswaan_announcements', 'recipient_emails')) {
                $table->text('recipient_emails')->nullable()->after('recipient_mode');
            }

            if (!Schema::hasColumn('kemahasiswaan_announcements', 'email_delivery_status')) {
                $table->enum('email_delivery_status', ['pending', 'queued', 'sending', 'sent', 'failed'])
                    ->default('pending')
                    ->after('reviewed_at');
            }

            if (!Schema::hasColumn('kemahasiswaan_announcements', 'email_dispatched_at')) {
                $table->timestamp('email_dispatched_at')->nullable()->after('email_delivery_status');
            }

            if (!Schema::hasColumn('kemahasiswaan_announcements', 'email_delivery_error')) {
                $table->text('email_delivery_error')->nullable()->after('email_dispatched_at');
            }
        });

        DB::table('kemahasiswaan_announcements')
            ->whereNull('recipient_mode')
            ->update([
                'recipient_mode' => 'all_students',
            ]);

        DB::table('kemahasiswaan_announcements')
            ->whereNull('email_delivery_status')
            ->update([
                'email_delivery_status' => 'pending',
            ]);

        DB::table('kemahasiswaan_announcements')
            ->where('publish_status', 'scheduled')
            ->update([
                'email_delivery_status' => 'queued',
            ]);

        DB::table('kemahasiswaan_announcements')
            ->where('publish_status', 'published')
            ->update([
                'email_delivery_status' => 'sent',
                'email_dispatched_at' => DB::raw('COALESCE(email_dispatched_at, updated_at)'),
            ]);
    }

    public function down(): void
    {
        if (!Schema::hasTable('kemahasiswaan_announcements')) {
            return;
        }

        Schema::table('kemahasiswaan_announcements', function (Blueprint $table): void {
            if (Schema::hasColumn('kemahasiswaan_announcements', 'email_delivery_error')) {
                $table->dropColumn('email_delivery_error');
            }

            if (Schema::hasColumn('kemahasiswaan_announcements', 'email_dispatched_at')) {
                $table->dropColumn('email_dispatched_at');
            }

            if (Schema::hasColumn('kemahasiswaan_announcements', 'email_delivery_status')) {
                $table->dropColumn('email_delivery_status');
            }

            if (Schema::hasColumn('kemahasiswaan_announcements', 'recipient_emails')) {
                $table->dropColumn('recipient_emails');
            }

            if (Schema::hasColumn('kemahasiswaan_announcements', 'recipient_mode')) {
                $table->dropColumn('recipient_mode');
            }
        });
    }
};
