<?php
$dbfile = __DIR__.'/../database.sqlite';
if (!file_exists($dbfile)) { echo "DB not found: $dbfile\n"; exit(1); }
$db = new PDO('sqlite:'.$dbfile);
$stmt = $db->query("PRAGMA table_info('organizations')");
$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
$names = array_map(fn($c) => $c['name'], $cols);
if (in_array('deleted_at', $names)) { echo "Column deleted_at already exists.\n"; exit(0); }
$db->exec("ALTER TABLE organizations ADD COLUMN deleted_at DATETIME NULL;");
echo "Added deleted_at column to organizations.\n";