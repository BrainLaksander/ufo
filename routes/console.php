<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('server:migrate {--create-db : Force create database if it does not exist (MySQL/MariaDB)} {--seed : Run seeder after migrations}', function () {
    $connectionName = (string) config('database.default', '');
    $connection = config("database.connections.{$connectionName}", []);
    $isLocal = app()->environment('local');
    $isCreateDbRequested = (bool) $this->option('create-db');
    $shouldCreateDb = $isCreateDbRequested || $isLocal;
    $identifierPattern = '/^[A-Za-z0-9_]+$/';

    $sanitizeMysqlIdentifier = static function (string $value, string $fallback) use ($identifierPattern): string {
        return preg_match($identifierPattern, $value) ? $value : $fallback;
    };

    $createDatabaseIfNeeded = function (array $connection) use ($identifierPattern, $sanitizeMysqlIdentifier): int {
        $driver = (string) ($connection['driver'] ?? '');

        if (!in_array($driver, ['mysql', 'mariadb'], true)) {
            $this->warn('Skipping database creation: --create-db only supports MySQL/MariaDB.');

            return self::SUCCESS;
        }

        $database = (string) ($connection['database'] ?? '');

        if ($database === '' || !preg_match($identifierPattern, $database)) {
            $this->error('Invalid DB_DATABASE value. Use only letters, numbers, and underscore.');

            return self::FAILURE;
        }

        $host = (string) ($connection['host'] ?? '127.0.0.1');
        $port = (string) ($connection['port'] ?? '3306');
        $username = (string) ($connection['username'] ?? 'root');
        $password = (string) ($connection['password'] ?? '');
        $socket = (string) ($connection['unix_socket'] ?? '');
        $charset = $sanitizeMysqlIdentifier((string) ($connection['charset'] ?? 'utf8mb4'), 'utf8mb4');
        $collation = $sanitizeMysqlIdentifier((string) ($connection['collation'] ?? 'utf8mb4_unicode_ci'), 'utf8mb4_unicode_ci');

        try {
            $dsn = $socket !== ''
                ? "mysql:unix_socket={$socket};charset={$charset}"
                : "mysql:host={$host};port={$port};charset={$charset}";

            $pdo = new \PDO($dsn, $username, $password, [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]);

            $safeDatabase = str_replace('`', '``', $database);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$safeDatabase}` CHARACTER SET {$charset} COLLATE {$collation}");

            $this->info("Database [{$database}] is ready.");
        } catch (\Throwable $e) {
            $this->error('Failed to create database automatically.');
            $this->error($e->getMessage());

            return self::FAILURE;
        }

        return self::SUCCESS;
    };

    if (!is_array($connection) || $connection === []) {
        $this->error("Database connection [{$connectionName}] is not configured.");

        return self::FAILURE;
    }

    if ($shouldCreateDb) {
        if ($isLocal && !$isCreateDbRequested) {
            $this->info('Local environment detected: database auto-create is enabled.');
        }

        $createDbExitCode = $createDatabaseIfNeeded($connection);

        if ($createDbExitCode !== self::SUCCESS) {
            return $createDbExitCode;
        }
    }

    $this->info('Running migrations...');
    $migrateExitCode = $this->call('migrate', ['--force' => true]);

    if ($migrateExitCode !== self::SUCCESS) {
        return $migrateExitCode;
    }

    if ((bool) $this->option('seed')) {
        $this->info('Running seeders...');
        $seedExitCode = $this->call('db:seed', ['--force' => true]);

        if ($seedExitCode !== self::SUCCESS) {
            return $seedExitCode;
        }
    }

    $this->info('Server migration finished successfully.');

    return self::SUCCESS;
})->purpose('Prepare a new server by creating database (optional), then running migrate and optional seed');
