-- Purpose:
-- 1) Audit last activity per table using newest updated_at/created_at
-- 2) Tag archive candidates (safe to archive, not delete)
--
-- Tested target: MySQL/MariaDB used by XAMPP
-- Assumption: current schema is selected via USE ufo;

SET @schema_name = DATABASE();

DROP TEMPORARY TABLE IF EXISTS tmp_table_activity;
CREATE TEMPORARY TABLE tmp_table_activity (
    table_name VARCHAR(128) NOT NULL,
    row_count BIGINT UNSIGNED NULL,
    has_created_at TINYINT(1) NOT NULL DEFAULT 0,
    has_updated_at TINYINT(1) NOT NULL DEFAULT 0,
    max_created_at DATETIME NULL,
    max_updated_at DATETIME NULL,
    last_activity_at DATETIME NULL,
    PRIMARY KEY (table_name)
);

INSERT INTO tmp_table_activity (table_name, row_count, has_created_at, has_updated_at)
SELECT
    t.table_name,
    t.table_rows,
    MAX(CASE WHEN c.column_name = 'created_at' THEN 1 ELSE 0 END) AS has_created_at,
    MAX(CASE WHEN c.column_name = 'updated_at' THEN 1 ELSE 0 END) AS has_updated_at
FROM information_schema.tables t
LEFT JOIN information_schema.columns c
    ON c.table_schema = t.table_schema
   AND c.table_name = t.table_name
   AND c.column_name IN ('created_at', 'updated_at')
WHERE t.table_schema = @schema_name
  AND t.table_type = 'BASE TABLE'
GROUP BY t.table_name, t.table_rows;

-- Build dynamic SQL to compute MAX(created_at/updated_at) only for tables that actually have the columns.
SET @sql_activity = (
    SELECT GROUP_CONCAT(stmt SEPARATOR ' ')
    FROM (
        SELECT CONCAT(
            'UPDATE tmp_table_activity SET max_created_at = (SELECT MAX(created_at) FROM `', table_name, '`), ',
            'max_updated_at = ',
            IF(has_updated_at = 1,
               CONCAT('(SELECT MAX(updated_at) FROM `', table_name, '`)'),
               'NULL'),
            ', last_activity_at = GREATEST(COALESCE(',
            IF(has_updated_at = 1,
               CONCAT('(SELECT MAX(updated_at) FROM `', table_name, '`)'),
               'NULL'),
            ', ''1000-01-01 00:00:00''), COALESCE((SELECT MAX(created_at) FROM `',
            table_name,
            '`), ''1000-01-01 00:00:00'')) ',
            'WHERE table_name = ''',
            table_name,
            ''';'
        ) AS stmt
        FROM tmp_table_activity
        WHERE has_created_at = 1
    ) x
);

SET @sql_activity_no_created = (
    SELECT GROUP_CONCAT(stmt SEPARATOR ' ')
    FROM (
        SELECT CONCAT(
            'UPDATE tmp_table_activity SET max_updated_at = (SELECT MAX(updated_at) FROM `', table_name, '`), ',
            'last_activity_at = (SELECT MAX(updated_at) FROM `', table_name, '`) ',
            'WHERE table_name = ''', table_name, ''';'
        ) AS stmt
        FROM tmp_table_activity
        WHERE has_created_at = 0 AND has_updated_at = 1
    ) y
);

SET @sql_activity = CONCAT(
    COALESCE(@sql_activity, ''),
    ' ',
    COALESCE(@sql_activity_no_created, '')
);

SET @sql_activity = NULLIF(TRIM(@sql_activity), '');

-- Execute only if there is at least one table with created_at/updated_at.
SET @sql_exec = IF(@sql_activity IS NULL, 'SELECT 1;', @sql_activity);
PREPARE stmt FROM @sql_exec;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Manual policy map for archive safety. Adjust retention_days as needed.
DROP TEMPORARY TABLE IF EXISTS tmp_archive_policy;
CREATE TEMPORARY TABLE tmp_archive_policy (
    table_name VARCHAR(128) PRIMARY KEY,
    policy_group VARCHAR(64) NOT NULL,
    archive_safe TINYINT(1) NOT NULL,
    retention_days INT NULL,
    notes VARCHAR(255) NULL
);

INSERT INTO tmp_archive_policy (table_name, policy_group, archive_safe, retention_days, notes)
VALUES
    ('activity_logs', 'history', 1, 180, 'Operational logs, usually safe to archive after retention window'),
    ('kemahasiswaan_activity_logs', 'history', 1, 180, 'Admin activity logs'),
    ('reports', 'history', 1, 365, 'Periodic reports often can be archived by period'),
    ('tasks', 'history', 1, 180, 'Completed tasks can be archived'),
    ('events', 'history', 1, 365, 'Past events can be archived by year'),
    ('submissions', 'history', 1, 365, 'Archive closed/approved/rejected historical submissions'),
    ('lost_found_items', 'history', 1, 180, 'Resolved items can be archived'),
    ('kemahasiswaan_announcements', 'history', 1, 365, 'Old announcements can be archived'),
    ('kemahasiswaan_schedules', 'history', 1, 365, 'Past schedules can be archived'),

    ('users', 'core', 0, NULL, 'Core identity data, do not archive blindly'),
    ('organizations', 'core', 0, NULL, 'Core domain table'),
    ('members', 'core', 0, NULL, 'Core domain table'),
    ('workflow_reference_values', 'reference', 0, NULL, 'Reference/master data'),

    ('migrations', 'system', 0, NULL, 'Laravel migration history'),
    ('cache', 'system', 0, NULL, 'Purge strategy, not archive'),
    ('cache_locks', 'system', 0, NULL, 'Purge strategy, not archive'),
    ('sessions', 'system', 0, NULL, 'TTL purge, not archive'),
    ('jobs', 'system', 0, NULL, 'Queue runtime table'),
    ('job_batches', 'system', 0, NULL, 'Queue runtime table'),
    ('failed_jobs', 'system', 0, NULL, 'Incident data; export then purge if needed'),
    ('password_reset_tokens', 'system', 0, NULL, 'TTL purge, not archive');

-- Final report:
-- - last activity per table
-- - archive recommendation by inactivity and policy
SELECT
    a.table_name,
    a.row_count,
    a.has_created_at,
    a.has_updated_at,
    a.max_created_at,
    a.max_updated_at,
    a.last_activity_at,
    p.policy_group,
    p.archive_safe,
    p.retention_days,
    p.notes,
    CASE
        WHEN p.archive_safe = 1
             AND p.retention_days IS NOT NULL
             AND a.last_activity_at IS NOT NULL
             AND a.last_activity_at < DATE_SUB(NOW(), INTERVAL p.retention_days DAY)
            THEN 'ARCHIVE_CANDIDATE'
        WHEN p.archive_safe = 1
            THEN 'ARCHIVE_REVIEW'
        WHEN p.archive_safe = 0
            THEN 'KEEP_ACTIVE'
        ELSE 'UNCLASSIFIED'
    END AS recommendation
FROM tmp_table_activity a
LEFT JOIN tmp_archive_policy p
    ON p.table_name = a.table_name
ORDER BY
    recommendation DESC,
    a.last_activity_at ASC,
    a.table_name ASC;

-- Optional: generate archive table DDL+copy SQL for one candidate table.
-- Example usage:
-- SET @target_table = 'activity_logs';
-- SET @archive_suffix = DATE_FORMAT(NOW(), '%Y%m');
-- SET @archive_table = CONCAT(@target_table, '_archive_', @archive_suffix);
-- SET @sql_clone = CONCAT('CREATE TABLE IF NOT EXISTS `', @archive_table, '` LIKE `', @target_table, '`;');
-- PREPARE s1 FROM @sql_clone; EXECUTE s1; DEALLOCATE PREPARE s1;
--
-- SET @sql_copy = CONCAT(
--   'INSERT INTO `', @archive_table, '` SELECT * FROM `', @target_table,
--   '` WHERE COALESCE(updated_at, created_at) < DATE_SUB(NOW(), INTERVAL 180 DAY);'
-- );
-- PREPARE s2 FROM @sql_copy; EXECUTE s2; DEALLOCATE PREPARE s2;
--
-- -- Delete from source only AFTER row-count/hash verification + backup checkpoint.
-- SET @sql_delete = CONCAT(
--   'DELETE FROM `', @target_table,
--   '` WHERE COALESCE(updated_at, created_at) < DATE_SUB(NOW(), INTERVAL 180 DAY);'
-- );
-- PREPARE s3 FROM @sql_delete; EXECUTE s3; DEALLOCATE PREPARE s3;
