<?php
$dbfile = __DIR__.'/../database.sqlite';
if (!file_exists($dbfile)) { echo "DB not found: $dbfile\n"; exit(1); }
$db = new PDO('sqlite:'.$dbfile);
$stmt = $db->query("PRAGMA table_info('members')");
$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
$names = array_map(fn($c) => $c['name'], $cols);
if (in_array('deleted_at', $names)) { echo "Column deleted_at already exists on members.\n"; exit(0); }
$result = $db->exec("ALTER TABLE members ADD COLUMN deleted_at DATETIME NULL;");
if ($result === false) {
    echo "Failed to add column deleted_at to members.\n";
    exit(1);
}
echo "Added deleted_at column to members.\n";