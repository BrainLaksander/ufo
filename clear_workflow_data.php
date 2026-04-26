<?php

use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    DB::table('submissions')->truncate();
    DB::table('reports')->truncate();
    DB::table('tasks')->truncate();
    DB::table('activity_logs')->truncate();
    echo "Successfully cleared submissions, reports, tasks, and activity_logs tables.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
