<?php
$dbfile = __DIR__.'/../database.sqlite';
if (!file_exists($dbfile)) { echo "DB not found: $dbfile\n"; exit(1); }
$db = new PDO('sqlite:'.$dbfile);
$stmt = $db->query("PRAGMA table_info('members')");
$cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
if (empty($cols)) { echo "(no members table)\n"; exit(0); }
foreach ($cols as $c) {
    echo $c['cid'] . ' ' . $c['name'] . ' ' . $c['type'] . "\n";
}
