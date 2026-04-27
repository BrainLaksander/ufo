<?php
// tools/prepare_deploy.php
// Usage: php tools/prepare_deploy.php [output.zip]

$output = $argv[1] ?? 'ufo_deploy.zip';

if (!extension_loaded('zip')) {
    echo "ZIP extension not available. Install/enable ext-zip.\n";
    exit(1);
}

$root = realpath(__DIR__ . '/..');
chdir($root);

$includes = [
    'app', 'bootstrap', 'config', 'database', 'public', 'resources', 'routes', 'vendor', 'composer.json', 'composer.lock', '.env.example'
];

$excludePaths = [
    '.git', 'node_modules', 'tests', 'storage/app/public/uploads', 'storage/app/public/lost-found', '.vscode'
];

$zip = new ZipArchive();
if ($zip->open($output, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    echo "Failed to create $output\n";
    exit(1);
}

function addPathToZip($zip, $path, $basePath, $excludePaths) {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), RecursiveIteratorIterator::LEAVES_ONLY);
    foreach ($files as $name => $file) {
        if (!$file->isFile()) continue;
        $filePath = $file->getRealPath();
        $relative = substr($filePath, strlen($basePath) + 1);
        foreach ($excludePaths as $ex) {
            if (strpos($relative, trim($ex, '/')) === 0 || strpos($relative, $ex) !== false) {
                continue 2;
            }
        }
        $zip->addFile($filePath, $relative);
    }
}

foreach ($includes as $inc) {
    $path = $root . DIRECTORY_SEPARATOR . $inc;
    if (!file_exists($path)) continue;
    if (is_file($path)) {
        $zip->addFile($path, basename($path));
        continue;
    }
    addPathToZip($zip, $path, $root, $excludePaths);
}

$zip->close();
echo "Created $output\n";

echo "Notes:\n";
echo " - Upload the zip to your Hostinger account and extract contents so that Laravel's 'public' folder is served as public_html.\n";
echo " - After upload, create a proper .env on server (do NOT upload sensitive local .env).\n";
echo " - Set MIGRATE_TOKEN in server .env if you want to run web migrations.\n";

return 0;
