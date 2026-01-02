<?php

/**
 * Script untuk update URL foto yang sudah ada di database
 * Mengubah URL dari format lama ke format baru
 */

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== Update Photo URLs Script ===\n\n";

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

                // Check if URL uses old format (storage path)
                if (strpos($oldUrl, '/storage/') !== false) {
                    // Extract path from old URL
                    $pathParts = explode('/storage/', $oldUrl);
                    if (count($pathParts) > 1) {
                        $path = $pathParts[1];

                        // Generate new URL
                        $newUrl = config('app.url') . '/storage/' . $path;

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

        if ($hasChanges) {
            $report->photo_evidence = $photoEvidence;
            $report->save();
        }
    }
}

echo "=== Update Complete ===\n";
echo "Total URLs updated: $updatedCount\n";
echo "Reports processed: " . $reports->count() . "\n";
