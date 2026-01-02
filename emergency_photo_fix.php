<?php

/**
 * Emergency Photo URL Fix
 * Script sederhana untuk fix URL foto yang tidak muncul
 */

// Load Laravel
require_once __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "<h1>🔧 Emergency Photo URL Fix</h1>";

// Step 1: Fix all photo URLs in database
echo "<h2>Step 1: Fix Photo URLs in Database</h2>";

try {
    $reports = \App\Models\Report::whereNotNull('photo_evidence')->get();
    $totalFixed = 0;

    foreach ($reports as $report) {
        if ($report->photo_evidence && is_array($report->photo_evidence)) {
            $updated = false;

            foreach ($report->photo_evidence as &$photo) {
                if (isset($photo['path'])) {
                    // Force URL to correct format
                    $correctUrl = 'https://ck.msapt.co.id/storage/' . $photo['path'];

                    if (!isset($photo['url']) || $photo['url'] !== $correctUrl) {
                        $photo['url'] = $correctUrl;
                        $updated = true;
                        $totalFixed++;

                        echo "<p style='color: blue;'>📝 Fixed Report #{$report->id}: {$photo['path']}</p>";
                    }
                }
            }

            if ($updated) {
                $report->photo_evidence = $report->photo_evidence;
                $report->save();
            }
        }
    }

    echo "<p style='color: green; font-size: 18px;'>✅ Fixed $totalFixed photo URLs!</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Error: " . $e->getMessage() . "</p>";
}

// Step 2: Create .htaccess if missing
echo "<h2>Step 2: Create .htaccess Files</h2>";

$storageHtaccess = __DIR__ . '/public/storage/.htaccess';
$photosHtaccess = __DIR__ . '/public/storage/photos/.htaccess';

if (!file_exists($storageHtaccess)) {
    $content = "Options +Indexes\n<IfModule mod_rewrite.c>\n    RewriteEngine On\n    RewriteCond %{REQUEST_FILENAME} !-f\n    RewriteCond %{REQUEST_FILENAME} !-d\n</IfModule>\n\n<FilesMatch \"\.(jpg|jpeg|png|gif|webp)$\">\n    Order Allow,Deny\n    Allow from all\n</FilesMatch>";

    if (file_put_contents($storageHtaccess, $content)) {
        echo "<p style='color: green;'>✅ Created public/storage/.htaccess</p>";
    } else {
        echo "<p style='color: red;'>❌ Failed to create storage .htaccess</p>";
    }
} else {
    echo "<p style='color: green;'>✅ storage/.htaccess already exists</p>";
}

if (!file_exists($photosHtaccess)) {
    $content = "Options +Indexes\n<FilesMatch \"\.(jpg|jpeg|png|gif|webp)$\">\n    Order Allow,Deny\n    Allow from all\n</FilesMatch>";

    if (file_put_contents($photosHtaccess, $content)) {
        echo "<p style='color: green;'>✅ Created public/storage/photos/.htaccess</p>";
    } else {
        echo "<p style='color: red;'>❌ Failed to create photos .htaccess</p>";
    }
} else {
    echo "<p style='color: green;'>✅ photos/.htaccess already exists</p>";
}

// Step 3: Test URL
echo "<h2>Step 3: Test Photo URL</h2>";

$testUrl = 'https://ck.msapt.co.id/storage/photos/';
echo "<p>Test URL: <a href='$testUrl' target='_blank'>$testUrl</a></p>";

$ch = curl_init($testUrl);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 5);
curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode == 200) {
    echo "<p style='color: green; font-size: 18px;'>✅ URL is ACCESSIBLE!</p>";
} else {
    echo "<p style='color: red; font-size: 18px;'>❌ URL returns HTTP $httpCode</p>";

    if ($httpCode == 403) {
        echo "<p style='color: orange;'>→ Check folder permissions (should be 755)</p>";
    } elseif ($httpCode == 404) {
        echo "<p style='color: orange;'>→ Check .htaccess and folder structure</p>";
    }
}

// Step 4: Show sample photo URLs
echo "<h2>Step 4: Sample Photo URLs</h2>";

$photoFolder = __DIR__ . '/public/storage/photos';
if (file_exists($photoFolder)) {
    $files = glob($photoFolder . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);

    if (!empty($files)) {
        echo "<p>Found " . count($files) . " photos:</p>";
        echo "<ul>";
        foreach (array_slice($files, 0, 3) as $file) {
            $filename = basename($file);
            $url = 'https://ck.msapt.co.id/storage/photos/' . $filename;
            echo "<li><a href='$url' target='_blank'>$filename</a></li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No photo files found in folder</p>";
    }
}

echo "<h2>🎯 Next Steps</h2>";
echo "<ol>";
echo "<li><strong>Refresh browser</strong> and check if photos appear</li>";
echo "<li><strong>Test photo upload</strong> with a new photo</li>";
echo "<li><strong>Check browser console</strong> for 404 errors</li>";
echo "<li><strong>If still not working</strong>, check hosting error logs</li>";
echo "</ol>";

echo "<p style='background: yellow; padding: 10px;'><strong>Important:</strong> After photos work, delete this script for security!</p>";
