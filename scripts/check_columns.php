<?php
$paths = [__DIR__.'/../database/database.sqlite', __DIR__.'/../database.sqlite'];
$found = false;
foreach ($paths as $p) {
    if (file_exists($p)) {
        $db = new PDO('sqlite:'.$p);
        $stmt = $db->query("PRAGMA table_info('organizations')");
        $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo "DB: $p\n";
        if (empty($cols)) {
            echo "(no table)\n";
        } else {
            foreach ($cols as $c) {
                echo $c['cid'] . ' ' . $c['name'] . ' ' . $c['type'] . "\n";
            }
        }
        $found = true;
    }
}
if (!$found) echo "No database files found.\n";
exit(0);
