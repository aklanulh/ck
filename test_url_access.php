<?php

/**
 * Simple URL Test Script
 * Test apakah URL foto bisa diakses langsung
 */

echo "<h2>Simple URL Access Test</h2>";

// Test basic URL generation
$appUrl = "https://ck.msapt.co.id";
$testUrls = [
    $appUrl . "/storage/photos/url_test.txt",
    $appUrl . "/storage/photos/",
    $appUrl . "/storage/"
];

echo "<h3>URL Access Test</h3>";
foreach ($testUrls as $url) {
    echo "<p>Testing: <a href='$url' target='_blank'>$url</a></p>";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    switch ($httpCode) {
        case 200:
            echo "<span style='color: green;'>✅ ACCESSIBLE</span><br>";
            break;
        case 404:
            echo "<span style='color: red;'>❌ NOT FOUND (404)</span><br>";
            break;
        case 403:
            echo "<span style='color: orange;'>🔒 FORBIDDEN (403)</span><br>";
            break;
        default:
            echo "<span style='color: red;'>❌ ERROR ($httpCode)</span><br>";
    }
}

// Create test file
echo "<h3>Create Test File</h3>";
$testContent = "Test file created at " . date('Y-m-d H:i:s');
$testFile = __DIR__ . '/public/storage/photos/url_test.txt';

if (file_put_contents($testFile, $testContent)) {
    echo "<p>✅ Test file created: <a href='" . $appUrl . "/storage/photos/url_test.txt' target='_blank'>url_test.txt</a></p>";
    echo "<p><small>Click the link above to test. If accessible, URL structure is correct.</small></p>";
} else {
    echo "<p>❌ Failed to create test file</p>";
}

// Check folder structure
echo "<h3>Folder Structure</h3>";
$baseFolder = __DIR__ . '/public/storage';
$folders = [
    $baseFolder => 'storage',
    $baseFolder . '/photos' => 'storage/photos'
];

foreach ($folders as $folder => $name) {
    $exists = file_exists($folder);
    $readable = $exists && is_readable($folder);

    echo "<p>$name: ";
    echo $exists ? "✅ EXISTS" : "❌ MISSING";
    echo " | " . ($readable ? "✅ READABLE" : "❌ NOT READABLE");
    echo "</p>";
}

echo "<h3>Manual Test Steps</h3>";
echo "<ol>";
echo "<li>Click the test file link above</li>";
echo "<li>If 404: Check .htaccess and folder permissions</li>";
echo "<li>If 403: Check folder permissions (should be 755)</li>";
echo "<li>If 200: URL structure is correct, issue is elsewhere</li>";
echo "<li>Delete test file after testing</li>";
echo "</ol>";

echo "<p><strong>After testing, run: rm " . basename($testFile) . "</strong></p>";
