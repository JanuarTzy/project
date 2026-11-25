<?php

// Ensure writable /tmp directories exist on Vercel's read-only filesystem
$storageDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/logs',
    '/tmp/bootstrap/cache',
];

foreach ($storageDirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Copy bundled SQLite database to /tmp so writes (e.g. login/POS) succeed
$bundledDb = __DIR__ . '/../database/database.sqlite';
$tmpDb = '/tmp/database.sqlite';
if (file_exists($bundledDb) && !file_exists($tmpDb)) {
    copy($bundledDb, $tmpDb);
}

require __DIR__ . '/../public/index.php';
