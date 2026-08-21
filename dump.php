<?php
require 'vendor/autoload.php';

use Ifsnop\Mysqldump as IMysqldump;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

try {
    $dsn = "mysql:host=" . $_ENV['DB_HOST'] . ";port=" . $_ENV['DB_PORT'] . ";dbname=" . $_ENV['DB_DATABASE'];
    $dump = new IMysqldump\Mysqldump($dsn, $_ENV['DB_USERNAME'], $_ENV['DB_PASSWORD'], [
        'add-drop-table' => true,
        'no-create-info' => false,
    ], [
        PDO::MYSQL_ATTR_SSL_CA => true,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false,
    ]);
    $dump->start('database/fantasync_backup.sql');
    echo "Dump completed successfully.\n";
} catch (\Exception $e) {
    echo 'mysqldump-php error: ' . $e->getMessage();
}
