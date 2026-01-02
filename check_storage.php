<?php

/**
 * Storage Configuration Checker untuk Catatan Kerja MSA
 * File ini untuk memverifikasi bahwa storage sudah terkonfigurasi dengan benar
 */

// Include Laravel bootstrap
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Storage Configuration Checker ===\n\n";

// Check storage configuration
$storageConfig = config('filesystems.disks.public');
echo "Storage Configuration:\n";
echo "- Driver: " . $storageConfig['driver'] . "\n";
echo "- Root: " . $storageConfig['root'] . "\n";
echo "- URL: " . $storageConfig['url'] . "\n";
echo "- Visibility: " . $storageConfig['visibility'] . "\n\n";

// Check if folders exist and are writable
$folders = [
    'public/storage' => 'Main storage folder',
    'public/storage/photos' => 'Photos storage folder',
    'storage/logs' => 'Logs folder',
    'storage/framework/cache' => 'Framework cache folder',
    'storage/framework/sessions' => 'Framework sessions folder',
    'storage/framework/views' => 'Framework views folder',
    'bootstrap/cache' => 'Bootstrap cache folder'
];

echo "Folder Permissions Check:\n";
foreach ($folders as $folder => $description) {
    $fullPath = __DIR__ . '/' . $folder;
    $exists = file_exists($fullPath);
    $writable = $exists ? is_writable($fullPath) : false;

    echo sprintf(
        "- %s: %s %s\n",
        $description,
        $exists ? "EXISTS" : "MISSING",
        $writable ? "(WRITABLE)" : "(NOT WRITABLE)"
    );

    if (!$exists) {
        echo "  → Create folder: mkdir -p " . $folder . "\n";
    } elseif (!$writable) {
        echo "  → Set permission: chmod 755 " . $folder . "\n";
    }
}

echo "\nURL Test:\n";
echo "- Base URL: " . config('app.url') . "\n";
echo "- Storage URL: " . $storageConfig['url'] . "\n";
echo "- Expected photo URL: " . config('app.url') . "/storage/photos/photo_example.jpg\n\n";

// Test storage functionality
try {
    $storage = \Illuminate\Support\Facades\Storage::disk('public');

    // Test write
    $testFile = 'test_' . time() . '.txt';
    $testContent = 'Storage test file - ' . date('Y-m-d H:i:s');

    if ($storage->put($testFile, $testContent)) {
        echo "✓ Storage WRITE test: SUCCESS\n";

        // Test read
        $readContent = $storage->get($testFile);
        if ($readContent === $testContent) {
            echo "✓ Storage READ test: SUCCESS\n";
        } else {
            echo "✗ Storage READ test: FAILED\n";
        }

        // Test URL generation
        $url = $storage->url($testFile);
        echo "✓ Generated URL: " . $url . "\n";

        // Clean up
        $storage->delete($testFile);
        echo "✓ Cleanup test file: SUCCESS\n";
    } else {
        echo "✗ Storage WRITE test: FAILED\n";
    }
} catch (Exception $e) {
    echo "✗ Storage test FAILED: " . $e->getMessage() . "\n";
}

echo "\n=== Recommendations ===\n";
echo "1. Pastikan folder public/storage/photos ada dan writable\n";
echo "2. Pastikan document root subdomain menunjuk ke folder public/\n";
echo "3. Jika foto tidak muncul, cek .htaccess di folder public/storage\n";
echo "4. Clear cache: php artisan config:cache\n";
echo "5. Test upload foto melalui web interface\n";

echo "\n=== Storage Check Complete ===\n";
