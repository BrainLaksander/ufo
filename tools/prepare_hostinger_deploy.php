<?php
// tools/prepare_hostinger_deploy.php
// Usage: php tools/prepare_hostinger_deploy.php [output.zip]
// Produces a zip where public files are at archive root and the rest of Laravel lives in /laravel

$output = $argv[1] ?? 'ufo_hostinger.zip';

if (!extension_loaded('zip')) {
    echo "ZIP extension not available. Install/enable ext-zip.\n";
    exit(1);
}

$root = realpath(__DIR__ . '/..');
$temp = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'ufo_deploy_' . uniqid();
mkdir($temp, 0755, true);

$laravelDir = $temp . DIRECTORY_SEPARATOR . 'laravel';
mkdir($laravelDir, 0755, true);

$includes = [
    'app', 'bootstrap', 'config', 'database', 'resources', 'routes', 'vendor', 'composer.json', 'composer.lock', '.env.example'
];

$publicDir = $root . DIRECTORY_SEPARATOR . 'public';

// copy a directory recursively
function copyRecursive($src, $dst, $exclude = []) {
    $dir = opendir($src);
    @mkdir($dst);
    while(false !== ($file = readdir($dir))) {
        if (($file != '.') && ($file != '..')) {
            $srcPath = $src . DIRECTORY_SEPARATOR . $file;
            $dstPath = $dst . DIRECTORY_SEPARATOR . $file;
            $skip = false;
            foreach ($exclude as $ex) {
                if (strpos($srcPath, $ex) !== false) { $skip = true; break; }
            }
            if ($skip) continue;
            if (is_dir($srcPath)) {
                copyRecursive($srcPath, $dstPath, $exclude);
            } else {
                copy($srcPath, $dstPath);
            }
        }
    }
    closedir($dir);
}

// copy laravel parts into /laravel
foreach ($includes as $inc) {
    $p = $root . DIRECTORY_SEPARATOR . $inc;
    if (!file_exists($p)) continue;
    if (is_dir($p)) {
        copyRecursive($p, $laravelDir . DIRECTORY_SEPARATOR . $inc, [
            $root . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'public',
        ]);
    } else {
        copy($p, $laravelDir . DIRECTORY_SEPARATOR . basename($p));
    }
}

// copy public files to temp root
copyRecursive($publicDir, $temp, []);

// Patch index.php to point to laravel directory
$indexPath = $temp . DIRECTORY_SEPARATOR . 'index.php';
if (file_exists($indexPath)) {
    $index = file_get_contents($indexPath);
    $index = str_replace("require __DIR__.'/../vendor/autoload.php'", "require __DIR__.'/laravel/vendor/autoload.php'", $index);
    $index = str_replace("require_once __DIR__.'/../bootstrap/app.php'", "require_once __DIR__.'/laravel/bootstrap/app.php'", $index);
    file_put_contents($indexPath, $index);
}

// Create a hostinger migration runner at root that points to laravel/bootstrap
$runPath = $temp . DIRECTORY_SEPARATOR . 'run_migrations.php';
$runContent = <<<'PHP'
<?php
// run_migrations.php for Hostinger (root)
declare(strict_types=1);
require __DIR__ . '/laravel/vendor/autoload.php';
$app = require_once __DIR__ . '/laravel/bootstrap/app.php';
$expected = getenv('MIGRATE_TOKEN') ?: null;
$provided = $_GET['token'] ?? null;
if (empty($expected) || $provided !== $expected) {
    http_response_code(403);
    echo "Forbidden\n";
    exit;
}
try {
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $status = $kernel->call('migrate', ['--force' => true]);
    echo "Migrate exit: $status\n";
    echo nl2br(htmlspecialchars($kernel->output()));
    if (isset($_GET['seed']) && in_array($_GET['seed'], ['1', 'true', 'yes'], true)) {
        $s = $kernel->call('db:seed', ['--force' => true]);
        echo "\nSeed exit: $s\n";
        echo nl2br(htmlspecialchars($kernel->output()));
    }
} catch (Throwable $e) {
    http_response_code(500);
    echo 'Error: ' . htmlspecialchars($e->getMessage());
}

PHP;
file_put_contents($runPath, $runContent);

// create zip
$zip = new ZipArchive();
if ($zip->open($output, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    echo "Failed to create $output\n";
    exit(1);
}

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($temp), RecursiveIteratorIterator::LEAVES_ONLY);
foreach ($it as $file) {
    if (!$file->isFile()) continue;
    $filePath = $file->getRealPath();
    $relative = substr($filePath, strlen($temp) + 1);
    $zip->addFile($filePath, $relative);
}

$zip->close();

// cleanup temp
function rrmdir($dir) {
    if (!is_dir($dir)) return;
    $items = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir, RecursiveDirectoryIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST);
    foreach ($items as $item) {
        if ($item->isDir()) rmdir($item->getRealPath()); else unlink($item->getRealPath());
    }
    rmdir($dir);
}
rrmdir($temp);

echo "Created $output (Hostinger layout).\n";

return 0;
