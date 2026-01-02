<?php

/**
 * Quick Photo URL Fix Script
 * Script ini untuk memperbaiki URL foto yang sudah ada di database
 * dan memastikan URL generation berfungsi dengan benar
 */

// Include Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "<h2>Quick Photo URL Fix</h2>";

// 1. Check current APP_URL
echo "<h3>1. APP_URL Check</h3>";
$appUrl = config('app.url');
echo "<p>Current APP_URL: <strong>$appUrl</strong></p>";
echo "<p>Expected: <strong>https://ck.msapt.co.id</strong></p>";

if ($appUrl !== 'https://ck.msapt.co.id') {
    echo "<p style='color: red;'>⚠️ APP_URL tidak sesuai! Update .env file</p>";
} else {
    echo "<p style='color: green;'>✅ APP_URL sudah benar</p>";
}

// 2. Test URL generation
echo "<h3>2. URL Generation Test</h3>";
$testPaths = ['photos/test.jpg', 'photos/sample.png'];
foreach ($testPaths as $path) {
    $url = config('app.url') . '/storage/' . $path;
    echo "<p>Path: $path → URL: <a href='$url' target='_blank'>$url</a></p>";
}

// 3. Check existing photos in database
echo "<h3>3. Database Photos Check</h3>";
try {
    $reports = \App\Models\Report::whereNotNull('photo_evidence')->get();
    echo "<p>Found {$reports->count()} reports with photos</p>";

    $fixedCount = 0;
    foreach ($reports as $report) {
        if ($report->photo_evidence && is_array($report->photo_evidence)) {
            $hasChanges = false;
            foreach ($report->photo_evidence as &$photo) {
                if (isset($photo['path'])) {
                    $oldUrl = $photo['url'] ?? '';
                    $newUrl = config('app.url') . '/storage/' . $photo['path'];

                    if ($oldUrl !== $newUrl) {
                        $photo['url'] = $newUrl;
                        $hasChanges = true;
                        $fixedCount++;

                        echo "<p style='color: orange;'>📝 Fixing Report ID {$report->id}: $oldUrl → $newUrl</p>";
                    }
                }
            }

            if ($hasChanges) {
                $report->photo_evidence = $report->photo_evidence;
                $report->save();
            }
        }
    }

    echo "<p style='color: green;'>✅ Fixed $fixedCount photo URLs</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

// 4. Check physical files
echo "<h3>4. Physical Files Check</h3>";
$photoFolder = public_path('storage/photos');
if (file_exists($photoFolder)) {
    $files = glob($photoFolder . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
    echo "<p>Found " . count($files) . " photo files in storage</p>";

    if (!empty($files)) {
        echo "<ul>";
        foreach (array_slice($files, 0, 5) as $file) {
            $filename = basename($file);
            $expectedUrl = config('app.url') . '/storage/photos/' . $filename;
            echo "<li><a href='$expectedUrl' target='_blank'>$filename</a></li>";
        }
        echo "</ul>";
    }
} else {
    echo "<p style='color: red;'>❌ Photo folder not found: $photoFolder</p>";
}

// 5. Create test file for URL testing
echo "<h3>5. Create Test File</h3>";
$testFile = public_path('storage/photos/url_test.txt');
if (file_put_contents($testFile, 'URL Test File - ' . date('Y-m-d H:i:s'))) {
    $testUrl = config('app.url') . '/storage/photos/url_test.txt';
    echo "<p>✅ Created test file: <a href='$testUrl' target='_blank'>$testUrl</a></p>";
    echo "<p><small>Click link to test if URL is accessible. If works, delete file afterwards.</small></p>";
}

// 6. Clear cache
echo "<h3>6. Clear Cache</h3>";
try {
    \Artisan::call('config:cache');
    \Artisan::call('view:clear');
    echo "<p style='color: green;'>✅ Cache cleared</p>";
} catch (Exception $e) {
    echo "<p style='color: orange;'>⚠️ Cache clear failed: " . $e->getMessage() . "</p>";
}

echo "<h3>7. Next Steps</h3>";
echo "<ol>";
echo "<li>Test the test file link above</li>";
echo "<li>If test file works, URL generation is correct</li>";
echo "<li>Check if photos display correctly now</li>";
echo "<li>If still not working, check .htaccess files</li>";
echo "<li>Delete test file after testing</li>";
echo "</ol>";

echo "<p><strong>Script completed! Refresh your browser and test photo display.</strong></p>";
