<?php

/**
 * Diagnostic Script untuk Foto Display Issues
 * Mengecek berbagai kemungkinan masalah foto tidak muncul
 */

echo "=== Foto Display Diagnostic ===\n\n";

// 1. Check APP_URL configuration
echo "1. APP_URL Configuration:\n";
$appUrl = config('app.url');
echo "   APP_URL: $appUrl\n";
echo "   Expected: https://ck.msapt.co.id\n\n";

// 2. Check storage configuration
echo "2. Storage Configuration:\n";
$storageConfig = config('filesystems.disks.public');
echo "   Root: " . $storageConfig['root'] . "\n";
echo "   URL: " . $storageConfig['url'] . "\n\n";

// 3. Check physical folder
echo "3. Physical Folder Check:\n";
$photoFolder = public_path('storage/photos');
echo "   Path: $photoFolder\n";
echo "   Exists: " . (file_exists($photoFolder) ? "YES" : "NO") . "\n";
echo "   Readable: " . (is_readable($photoFolder) ? "YES" : "NO") . "\n";

if (file_exists($photoFolder)) {
    $files = glob($photoFolder . '/*.{jpg,jpeg,png,gif}', GLOB_BRACE);
    echo "   Photo files found: " . count($files) . "\n";

    if (!empty($files)) {
        echo "   Sample files:\n";
        foreach (array_slice($files, 0, 3) as $file) {
            $filename = basename($file);
            echo "     - $filename\n";
        }
    }
}
echo "\n";

// 4. Check .htaccess in storage folder
echo "4. .htaccess Check:\n";
$htaccessPath = public_path('storage/.htaccess');
echo "   .htaccess exists: " . (file_exists($htaccessPath) ? "YES" : "NO") . "\n";
if (file_exists($htaccessPath)) {
    echo "   .htaccess content:\n";
    echo "   " . file_get_contents($htaccessPath) . "\n";
}
echo "\n";

// 5. Test URL generation
echo "5. URL Generation Test:\n";
$testPath = 'photos/test_photo.jpg';
$generatedUrl = config('app.url') . '/storage/' . $testPath;
echo "   Test path: $testPath\n";
echo "   Generated URL: $generatedUrl\n";
echo "   Expected format: https://ck.msapt.co.id/storage/photos/test_photo.jpg\n\n";

// 6. Check if URL is accessible
echo "6. URL Accessibility Test:\n";
if (function_exists('curl_init')) {
    $ch = curl_init($generatedUrl);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    echo "   HTTP Status: $httpCode\n";
    if ($httpCode === 200) {
        echo "   Status: ACCESSIBLE ✓\n";
    } elseif ($httpCode === 404) {
        echo "   Status: NOT FOUND (404) ✗\n";
    } elseif ($httpCode === 403) {
        echo "   Status: FORBIDDEN (403) ✗\n";
    } else {
        echo "   Status: ERROR ($httpCode) ✗\n";
    }
} else {
    echo "   cURL not available, cannot test URL\n";
}
echo "\n";

// 7. Check actual photo URLs in database
echo "7. Database Photo URLs Check:\n";
try {
    $reports = \App\Models\Report::whereNotNull('photo_evidence')->limit(3)->get();
    echo "   Reports with photos: " . $reports->count() . "\n";

    foreach ($reports as $report) {
        echo "   Report ID: {$report->id}\n";
        if ($report->photo_evidence && is_array($report->photo_evidence)) {
            foreach ($report->photo_evidence as $photo) {
                if (isset($photo['url'])) {
                    echo "     Photo URL: {$photo['url']}\n";
                    echo "     Photo Path: " . ($photo['path'] ?? 'N/A') . "\n";
                }
            }
        }
        echo "\n";
    }
} catch (Exception $e) {
    echo "   Error: " . $e->getMessage() . "\n";
}

echo "\n=== Recommendations ===\n";
echo "1. Pastikan APP_URL di .env adalah 'https://ck.msapt.co.id'\n";
echo "2. Pastikan folder public/storage/photos ada dan permissions 755\n";
echo "3. Buat .htaccess di public/storage/ dengan content yang benar\n";
echo "4. Clear cache: php artisan config:cache\n";
echo "5. Test dengan upload foto baru\n";
echo "6. Cek error log hosting untuk 404/403 errors\n";

echo "\n=== Diagnostic Complete ===\n";
