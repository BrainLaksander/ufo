<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kemahasiswaan_ukm_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name', 120);
            $table->string('email', 120)->unique();
            $table->string('position', 80)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('last_password_reset_at')->nullable();
            $table->timestamps();

            $table->index(['organization_id', 'status'], 'kmsa_account_org_status_idx');
        });

        Schema::create('kemahasiswaan_announcements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ukm_account_id')->nullable()->constrained('kemahasiswaan_ukm_accounts')->nullOnDelete();
            $table->string('title', 160);
            $table->string('category', 80)->nullable();
            $table->string('target_audience', 120)->nullable();
            $table->text('summary')->nullable();
            $table->longText('content')->nullable();
            $table->dateTime('publish_at')->nullable();
            $table->enum('publish_status', ['draft', 'scheduled', 'published', 'archived'])->default('draft');
            $table->enum('email_review_status', ['pending', 'approved', 'rejected', 'revision'])->default('pending');
            $table->text('email_review_note')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();

            $table->index(['publish_status', 'email_review_status'], 'kmsa_ann_status_idx');
            $table->index(['ukm_account_id', 'created_at'], 'kmsa_ann_account_created_idx');
        });

        Schema::create('kemahasiswaan_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ukm_account_id')->nullable()->constrained('kemahasiswaan_ukm_accounts')->nullOnDelete();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->string('action', 120);
            $table->text('description');
            $table->json('metadata')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent', 255)->nullable();
            $table->timestamps();

            $table->index(['ukm_account_id', 'created_at'], 'kmsa_log_account_created_idx');
            $table->index(['organization_id', 'created_at'], 'kmsa_log_org_created_idx');
        });

        Schema::create('kemahasiswaan_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->nullable()->constrained('organizations')->nullOnDelete();
            $table->string('title', 150);
            $table->dateTime('start_at');
            $table->dateTime('end_at')->nullable();
            $table->string('location', 160);
            $table->enum('status', ['planned', 'ongoing', 'completed', 'cancelled'])->default('planned');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['start_at', 'status'], 'kmsa_sched_start_status_idx');
        });

        if (Schema::hasTable('submissions') && !Schema::hasColumn('submissions', 'reviewed_by_department_user_id')) {
            Schema::table('submissions', function (Blueprint $table) {
                $table->foreignId('reviewed_by_department_user_id')
                    ->nullable()
                    ->after('member_id')
                    ->constrained('users')
                    ->nullOnDelete();

                $table->text('department_review_note')->nullable()->after('feedback');
                $table->timestamp('reviewed_at')->nullable()->after('approved_date');
                $table->index(['status', 'reviewed_at'], 'subm_status_reviewed_idx');
            });
        }

        if (Schema::hasTable('reports') && !Schema::hasColumn('reports', 'reviewed_by_department_user_id')) {
            Schema::table('reports', function (Blueprint $table) {
                $table->foreignId('reviewed_by_department_user_id')
                    ->nullable()
                    ->after('member_id')
                    ->constrained('users')
                    ->nullOnDelete();

                $table->text('department_review_note')->nullable()->after('reviewer_notes');
                $table->timestamp('reviewed_at')->nullable()->after('approved_date');
                $table->index(['status', 'reviewed_at'], 'rpts_status_reviewed_idx');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('reports') && Schema::hasColumn('reports', 'reviewed_by_department_user_id')) {
            Schema::table('reports', function (Blueprint $table) {
                $table->dropIndex('rpts_status_reviewed_idx');
                $table->dropForeign(['reviewed_by_department_user_id']);
                $table->dropColumn(['reviewed_by_department_user_id', 'department_review_note', 'reviewed_at']);
            });
        }

        if (Schema::hasTable('submissions') && Schema::hasColumn('submissions', 'reviewed_by_department_user_id')) {
            Schema::table('submissions', function (Blueprint $table) {
                $table->dropIndex('subm_status_reviewed_idx');
                $table->dropForeign(['reviewed_by_department_user_id']);
                $table->dropColumn(['reviewed_by_department_user_id', 'department_review_note', 'reviewed_at']);
            });
        }

        Schema::dropIfExists('kemahasiswaan_schedules');
        Schema::dropIfExists('kemahasiswaan_activity_logs');
        Schema::dropIfExists('kemahasiswaan_announcements');
        Schema::dropIfExists('kemahasiswaan_ukm_accounts');
    }
};
