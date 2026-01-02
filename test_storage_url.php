<?php

/**
 * Test URL Generation untuk Storage
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Storage URL Generation Test ===\n\n";

// Test storage configuration
$storageConfig = config('filesystems.disks.public');
echo "Current Storage Config:\n";
echo "- Root: " . $storageConfig['root'] . "\n";
echo "- URL: " . $storageConfig['url'] . "\n\n";

// Test URL generation
$storage = \Illuminate\Support\Facades\Storage::disk('public');

$testPaths = [
    'photos/photo_test_123.jpg',
    'photos/sample_image.png',
    'documents/report.pdf'
];

foreach ($testPaths as $path) {
    $url = $storage->url($path);
    echo "Path: $path\n";
    echo "Generated URL: $url\n";
    echo "Expected: " . config('app.url') . "/storage/$path\n";
    echo "Match: " . ($url === config('app.url') . "/storage/$path" ? "YES" : "NO") . "\n\n";
}

echo "=== App Configuration ===\n";
echo "APP_URL: " . config('app.url') . "\n";
echo "Asset URL: " . asset('storage/photos/test.jpg') . "\n\n";

echo "=== Physical Path Test ===\n";
$publicPath = public_path('storage/photos');
echo "Public storage path: $publicPath\n";
echo "Exists: " . (file_exists($publicPath) ? "YES" : "NO") . "\n";
echo "Writable: " . (is_writable($publicPath) ? "YES" : "NO") . "\n";
