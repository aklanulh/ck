<?php

/**
 * Hostinger Deployment Script
 * Run this script after uploading files to Hostinger
 */

echo "Starting Laravel deployment for Hostinger...\n";

// Set environment variables for production
$_ENV['APP_ENV'] = 'production';
$_ENV['APP_DEBUG'] = 'false';

// Check if .env exists
if (!file_exists('.env')) {
    echo "ERROR: .env file not found. Please create it from env.production template.\n";
    exit(1);
}

// Check required directories
$required_dirs = [
    'storage/logs',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'bootstrap/cache'
];

foreach ($required_dirs as $dir) {
    if (!is_dir($dir)) {
        echo "Creating directory: $dir\n";
        mkdir($dir, 0755, true);
    }
}

// Set permissions
echo "Setting permissions...\n";
$dirs_to_chmod = [
    'storage',
    'bootstrap/cache'
];

foreach ($dirs_to_chmod as $dir) {
    if (is_dir($dir)) {
        chmod($dir, 0755);
        echo "Set 755 permission for: $dir\n";
    }
}

// Clear caches
echo "Clearing caches...\n";
if (function_exists('shell_exec')) {
    shell_exec('php artisan config:clear');
    shell_exec('php artisan route:clear');
    shell_exec('php artisan view:clear');
    shell_exec('php artisan cache:clear');
}

// Create storage link
echo "Creating storage link...\n";
if (!is_link('public/storage')) {
    if (function_exists('symlink')) {
        symlink('../storage/app/public', 'public/storage');
        echo "Storage link created successfully.\n";
    }
}

echo "Deployment setup completed!\n";
echo "Next steps:\n";
echo "1. Update .env with your database credentials\n";
echo "2. Run: composer install --no-dev\n";
echo "3. Run: php artisan key:generate\n";
echo "4. Run: php artisan migrate\n";
echo "5. Run: npm install && npm run build\n";
