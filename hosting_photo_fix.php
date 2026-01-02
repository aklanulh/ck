<?php

/**
 * Hosting Photo Fix Script
 * Jalankan script ini di hosting Hostinger untuk memperbaiki masalah foto
 */

echo "<h2>Photo Display Fix for Hosting</h2>";

// 1. Check current configuration
echo "<h3>1. Current Configuration</h3>";
echo "<p><strong>APP_URL:</strong> " . (config('app.url') ?? 'Not configured') . "</p>";
echo "<p><strong>Storage Root:</strong> " . config('filesystems.disks.public.root') . "</p>";
echo "<p><strong>Storage URL:</strong> " . config('filesystems.disks.public.url') . "</p>";

// 2. Check folders
echo "<h3>2. Folder Check</h3>";
$folders = [
    'public/storage' => 'Main storage folder',
    'public/storage/photos' => 'Photos folder'
];

foreach ($folders as $folder => $desc) {
    $path = base_path($folder);
    $exists = file_exists($path);
    $writable = $exists && is_writable($path);

    echo "<p><strong>$desc:</strong> ";
    echo $exists ? "✓ EXISTS" : "✗ MISSING";
    if ($exists) {
        echo " | " . ($writable ? "✓ WRITABLE" : "✗ NOT WRITABLE");
    }
    echo "</p>";
}

// 3. Create .htaccess files
echo "<h3>3. Creating .htaccess Files</h3>";

$storageHtaccess = base_path('public/storage/.htaccess');
$photosHtaccess = base_path('public/storage/photos/.htaccess');

// Storage .htaccess
$storageContent = 'Options +Indexes
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
</IfModule>

<FilesMatch "\.(jpg|jpeg|png|gif|webp)$">
    Order Allow,Deny
    Allow from all
</FilesMatch>

<IfModule mod_mime.c>
    AddType image/jpeg .jpg .jpeg
    AddType image/png .png
    AddType image/gif .gif
    AddType image/webp .webp
</IfModule>

<Files "*.php">
    Deny from all
</Files>';

if (file_put_contents($storageHtaccess, $storageContent)) {
    echo "<p>✓ Created public/storage/.htaccess</p>";
} else {
    echo "<p>✗ Failed to create public/storage/.htaccess</p>";
}

// Photos .htaccess
$photosContent = 'Options +Indexes
<IfModule mod_rewrite.c>
    RewriteEngine Off
</IfModule>

<FilesMatch "\.(jpg|jpeg|png|gif|webp)$">
    Order Allow,Deny
    Allow from all
</FilesMatch>

<IfModule mod_mime.c>
    AddType image/jpeg .jpg .jpeg
    AddType image/png .png
    AddType image/gif .gif
    AddType image/webp .webp
</IfModule>

<Files "*.php">
    Deny from all
</Files>';

if (file_put_contents($photosHtaccess, $photosContent)) {
    echo "<p>✓ Created public/storage/photos/.htaccess</p>";
} else {
    echo "<p>✗ Failed to create public/storage/photos/.htaccess</p>";
}

// 4. Create photos folder if not exists
$photosFolder = base_path('public/storage/photos');
if (!file_exists($photosFolder)) {
    if (mkdir($photosFolder, 0755, true)) {
        echo "<p>✓ Created photos folder</p>";
    } else {
        echo "<p>✗ Failed to create photos folder</p>";
    }
}

// 5. Test URL generation
echo "<h3>4. URL Generation Test</h3>";
$testPath = 'photos/test.jpg';
$expectedUrl = config('app.url') . '/storage/' . $testPath;
echo "<p><strong>Test URL:</strong> <a href='$expectedUrl' target='_blank'>$expectedUrl</a></p>";

// 6. Check existing photos
echo "<h3>5. Existing Photos</h3>";
$photoFolder = base_path('public/storage/photos');
if (file_exists($photoFolder)) {
    $files = glob($photoFolder . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);
    echo "<p>Found " . count($files) . " photo files:</p>";

    if (!empty($files)) {
        echo "<ul>";
        foreach (array_slice($files, 0, 5) as $file) {
            $filename = basename($file);
            $url = config('app.url') . '/storage/photos/' . $filename;
            echo "<li><a href='$url' target='_blank'>$filename</a></li>";
        }
        echo "</ul>";
    }
} else {
    echo "<p>No photos folder found</p>";
}

// 7. Clear cache
echo "<h3>6. Clear Cache</h3>";
try {
    \Artisan::call('config:cache');
    echo "<p>✓ Config cache cleared</p>";
} catch (Exception $e) {
    echo "<p>✗ Failed to clear cache: " . $e->getMessage() . "</p>";
}

echo "<h3>7. Manual Steps</h3>";
echo "<p>If photos still don't display:</p>";
echo "<ol>";
echo "<li>Check that document root points to: <strong>public_html/ck/public</strong></li>";
echo "<li>Verify folder permissions are 755</li>";
echo "<li>Test with a simple image URL in browser</li>";
echo "<li>Check hosting error logs</li>";
echo "</ol>";

echo "<p><em>Script completed. Refresh your browser and test photo display.</em></p>";
