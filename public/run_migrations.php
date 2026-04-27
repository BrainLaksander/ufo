<?php
// public/run_migrations.php
// Web-accessible migration runner. Protect by setting MIGRATE_TOKEN in .env on the server.

declare(strict_types=1);

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';

// Load environment variable token from .env or server env
$expected = getenv('MIGRATE_TOKEN') ?: null;

// Simple token check via GET param
$provided = $_GET['token'] ?? null;

if (empty($expected) || $provided !== $expected) {
    http_response_code(403);
    echo "Forbidden\n";
    exit;
}

try {
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

    // Run migrations
    $status = $kernel->call('migrate', ['--force' => true]);
    echo "Migrate exit: $status\n";
    echo nl2br(htmlspecialchars($kernel->output()));

    // Optionally run seed if requested
    if (isset($_GET['seed']) && in_array($_GET['seed'], ['1', 'true', 'yes'], true)) {
        $s = $kernel->call('db:seed', ['--force' => true]);
        echo "\nSeed exit: $s\n";
        echo nl2br(htmlspecialchars($kernel->output()));
    }

} catch (Throwable $e) {
    http_response_code(500);
    echo 'Error: ' . htmlspecialchars($e->getMessage());
}
