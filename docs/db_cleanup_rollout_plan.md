# Database Cleanup Rollout Plan (Archive-First)

## Objective
- Audit latest per-table activity using created_at/updated_at.
- Mark archive-safe tables first (no hard delete in early stages).
- Execute phased cleanup with explicit rollback checkpoints.

## Scope and Principle
- Priority: archive first, delete later.
- Never hard-delete core tables without business sign-off.
- Every phase requires:
  1) backup/snapshot checkpoint,
  2) verification query,
  3) rollback path.

## Recommended Phases

### Phase 0: Baseline and Backup
1. Take full DB backup.
2. Record database size and table row counts.
3. Run SQL audit report from database/sql/audit_last_activity_and_archive_candidates.sql.

Exit criteria:
- Backup file verified and restorable.
- Audit report exported.

Rollback:
- Restore full backup.

### Phase 1: Archive Historical Log-Like Tables
Candidate tables:
- activity_logs
- kemahasiswaan_activity_logs
- failed_jobs (optional: export+purge policy)

Actions:
1. Create archive table clones (suffix by month).
2. Copy rows older than retention policy.
3. Verify source-vs-archive row counts and random checks.
4. Delete archived rows from source only after verification.

Exit criteria:
- Counts match expected totals.
- Application log screens still work for recent data.

Rollback:
- Reinsert from archive table to source by same filter.
- Or restore from pre-phase backup.

### Phase 2: Archive Operational History
Candidate tables:
- reports
- tasks
- events
- submissions
- lost_found_items
- kemahasiswaan_announcements
- kemahasiswaan_schedules

Actions:
1. Apply per-table retention window.
2. Archive by closed/final status and date.
3. Run app smoke test after each table batch.

Exit criteria:
- No regression on dashboard/list/detail pages.
- No foreign key violations.

Rollback:
- Reinsert archived slice to source table.
- If issue scope is broad, restore phase snapshot.

### Phase 3: System Table Hygiene (Purge, Not Archive)
Tables:
- sessions
- cache
- cache_locks
- jobs/job_batches (runtime handling)
- password_reset_tokens

Actions:
1. Apply TTL cleanup commands.
2. Schedule periodic purge job.

Exit criteria:
- No queue/session errors after purge window.

Rollback:
- Usually not needed for ephemeral data.
- If needed, restore backup snapshot.

## Tables Usually Not Archived by Default
- users
- organizations
- members
- workflow_reference_values
- migrations

Reason:
- Core identity/master/reference/system metadata tables.

## Verification Queries (Minimum)
1. Pre/post counts per table.
2. Recent-row availability check (last 30 days).
3. FK integrity check (or app-level consistency checks where FK absent).
4. Slow query comparison before/after cleanup.

## Rollback Playbook Template
1. Stop write-heavy jobs (queue workers/cron) temporarily.
2. Restore affected table from archive or snapshot.
3. Rebuild indexes if needed.
4. Run integrity checks.
5. Re-enable jobs.
6. Document incident and retention rule adjustment.

## Suggested Schedule
- Week 1: Phase 0 + Phase 1 on low-risk log tables.
- Week 2: Phase 2 for 1-2 tables per maintenance window.
- Week 3: Continue Phase 2 and evaluate performance gains.
- Week 4: Phase 3 automation and monitoring baseline.

## Operational Notes
- Prefer off-hours windows for copy/delete operations.
- Use batched deletes for large tables.
- Keep archive tables in same schema first; move to cold storage later if needed.
- Add monitoring: table size growth and row churn alerts.
