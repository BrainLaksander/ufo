<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!$this->isMySqlFamily()) {
            return;
        }

        if (Schema::hasTable('users') && Schema::hasColumn('users', 'role')) {
            $this->addIndexIfMissing('users', 'users_role_idx', function (Blueprint $table): void {
                $table->index('role', 'users_role_idx');
            });
        }

        if (
            Schema::hasTable('users')
            && Schema::hasColumn('users', 'role')
            && Schema::hasColumn('users', 'organization_id')
        ) {
            $this->addIndexIfMissing('users', 'users_role_organization_idx', function (Blueprint $table): void {
                $table->index(['role', 'organization_id'], 'users_role_organization_idx');
            });
        }

        if (
            Schema::hasTable('users')
            && Schema::hasTable('organizations')
            && Schema::hasColumn('users', 'organization_id')
            && !$this->hasForeignKey('users', 'users_organization_id_foreign')
        ) {
            Schema::table('users', function (Blueprint $table): void {
                $table
                    ->foreign('organization_id', 'users_organization_id_foreign')
                    ->references('id')
                    ->on('organizations')
                    ->nullOnDelete();
            });
        }

        if (
            Schema::hasTable('events')
            && Schema::hasColumn('events', 'organization_id')
            && Schema::hasColumn('events', 'start_date')
            && Schema::hasColumn('events', 'status')
        ) {
            $this->addIndexIfMissing('events', 'events_org_start_status_idx', function (Blueprint $table): void {
                $table->index(['organization_id', 'start_date', 'status'], 'events_org_start_status_idx');
            });
        }

        if (
            Schema::hasTable('submissions')
            && Schema::hasColumn('submissions', 'organization_id')
            && Schema::hasColumn('submissions', 'status')
            && Schema::hasColumn('submissions', 'submitted_date')
        ) {
            $this->addIndexIfMissing('submissions', 'submissions_org_status_submitted_idx', function (Blueprint $table): void {
                $table->index(['organization_id', 'status', 'submitted_date'], 'submissions_org_status_submitted_idx');
            });
        }

        if (
            Schema::hasTable('reports')
            && Schema::hasColumn('reports', 'organization_id')
            && Schema::hasColumn('reports', 'status')
            && Schema::hasColumn('reports', 'submitted_date')
        ) {
            $this->addIndexIfMissing('reports', 'reports_org_status_submitted_idx', function (Blueprint $table): void {
                $table->index(['organization_id', 'status', 'submitted_date'], 'reports_org_status_submitted_idx');
            });
        }

        if (
            Schema::hasTable('tasks')
            && Schema::hasColumn('tasks', 'organization_id')
            && Schema::hasColumn('tasks', 'status')
            && Schema::hasColumn('tasks', 'deadline')
        ) {
            $this->addIndexIfMissing('tasks', 'tasks_org_status_deadline_idx', function (Blueprint $table): void {
                $table->index(['organization_id', 'status', 'deadline'], 'tasks_org_status_deadline_idx');
            });
        }

        if (
            Schema::hasTable('kemahasiswaan_announcements')
            && Schema::hasColumn('kemahasiswaan_announcements', 'email_review_status')
            && Schema::hasColumn('kemahasiswaan_announcements', 'reviewed_at')
        ) {
            $this->addIndexIfMissing('kemahasiswaan_announcements', 'kmsa_ann_review_status_idx', function (Blueprint $table): void {
                $table->index(['email_review_status', 'reviewed_at'], 'kmsa_ann_review_status_idx');
            });
        }

        if (
            Schema::hasTable('kemahasiswaan_activity_logs')
            && Schema::hasColumn('kemahasiswaan_activity_logs', 'action')
            && Schema::hasColumn('kemahasiswaan_activity_logs', 'created_at')
        ) {
            $this->addIndexIfMissing('kemahasiswaan_activity_logs', 'kmsa_log_action_created_idx', function (Blueprint $table): void {
                $table->index(['action', 'created_at'], 'kmsa_log_action_created_idx');
            });
        }
    }

    public function down(): void
    {
        if (!$this->isMySqlFamily()) {
            return;
        }

        if ($this->hasForeignKey('users', 'users_organization_id_foreign')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->dropForeign('users_organization_id_foreign');
            });
        }

        $this->dropIndexIfExists('users', 'users_role_idx');
        $this->dropIndexIfExists('users', 'users_role_organization_idx');
        $this->dropIndexIfExists('events', 'events_org_start_status_idx');
        $this->dropIndexIfExists('submissions', 'submissions_org_status_submitted_idx');
        $this->dropIndexIfExists('reports', 'reports_org_status_submitted_idx');
        $this->dropIndexIfExists('tasks', 'tasks_org_status_deadline_idx');
        $this->dropIndexIfExists('kemahasiswaan_announcements', 'kmsa_ann_review_status_idx');
        $this->dropIndexIfExists('kemahasiswaan_activity_logs', 'kmsa_log_action_created_idx');
    }

    private function isMySqlFamily(): bool
    {
        return in_array(Schema::getConnection()->getDriverName(), ['mysql', 'mariadb'], true);
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        if (!Schema::hasTable($table)) {
            return false;
        }

        return DB::table('information_schema.statistics')
            ->where('table_schema', DB::getDatabaseName())
            ->where('table_name', $table)
            ->where('index_name', $indexName)
            ->exists();
    }

    private function hasForeignKey(string $table, string $foreignKeyName): bool
    {
        if (!Schema::hasTable($table)) {
            return false;
        }

        return DB::table('information_schema.table_constraints')
            ->where('constraint_schema', DB::getDatabaseName())
            ->where('table_name', $table)
            ->where('constraint_name', $foreignKeyName)
            ->where('constraint_type', 'FOREIGN KEY')
            ->exists();
    }

    private function addIndexIfMissing(string $table, string $indexName, \Closure $callback): void
    {
        if ($this->hasIndex($table, $indexName)) {
            return;
        }

        Schema::table($table, $callback);
    }

    private function dropIndexIfExists(string $table, string $indexName): void
    {
        if (!Schema::hasTable($table) || !$this->hasIndex($table, $indexName)) {
            return;
        }

        Schema::table($table, function (Blueprint $table) use ($indexName): void {
            $table->dropIndex($indexName);
        });
    }
};
