<?php

/**
 * Update Photo URLs untuk Hosting Structure
 * Mengubah URL dari format lama ke format baru dengan /ck/public/
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Update Photo URLs for Hosting Structure ===\n\n";

use App\Models\Report;

// Get all reports with photo_evidence
$reports = Report::whereNotNull('photo_evidence')->get();

echo "Found " . $reports->count() . " reports with photos\n\n";

$updatedCount = 0;

foreach ($reports as $report) {
    $photoEvidence = $report->photo_evidence;

    if (is_array($photoEvidence)) {
        $hasChanges = false;

        foreach ($photoEvidence as &$photo) {
            if (isset($photo['url'])) {
                $oldUrl = $photo['url'];

                // Check if URL needs updating
                if (strpos($oldUrl, '/storage/') !== false && strpos($oldUrl, '/public/storage/') === false) {
                    // Extract path from old URL
                    if (strpos($oldUrl, '/storage/') !== false) {
                        $pathParts = explode('/storage/', $oldUrl);
                        if (count($pathParts) > 1) {
                            $path = $pathParts[1];

                            // Generate new URL with correct hosting structure
                            $newUrl = config('app.url') . '/public/storage/' . $path;

                            if ($oldUrl !== $newUrl) {
                                $photo['url'] = $newUrl;
                                $hasChanges = true;

                                echo "Updated URL for Report ID: {$report->id}\n";
                                echo "  Old: $oldUrl\n";
                                echo "  New: $newUrl\n\n";

                                $updatedCount++;
                            }
                        }
                    }
                }
            }
        }

        if ($hasChanges) {
            $report->photo_evidence = $photoEvidence;
            $report->save();
        }
    }
}

echo "=== Update Complete ===\n";
echo "Total URLs updated: $updatedCount\n";
echo "Reports processed: " . $reports->count() . "\n";

echo "\n=== New URL Format ===\n";
echo "From: https://ck.msapt.co.id/storage/photos/photo_xxx.jpg\n";
echo "To:   https://ck.msapt.co.id/public/storage/photos/photo_xxx.jpg\n";

echo "\n=== Next Steps ===\n";
echo "1. Upload this file to hosting\n";
echo "2. Run: php update_hosting_photo_urls.php\n";
echo "3. Clear cache: php artisan config:cache\n";
echo "4. Test photo display in website\n";
