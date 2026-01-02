#!/bin/bash

# Setup Storage untuk Catatan Kerja MSA di Hostinger
# Subdomain: ck.msapt.co.id
# Path: public_html/ck/public

echo "=== Setup Storage Catatan Kerja MSA ==="

# Buat folder storage yang diperlukan
echo "Membuat folder storage..."

mkdir -p public/storage/photos
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Set permissions
echo "Mengatur folder permissions..."

chmod 755 public/storage/
chmod 755 public/storage/photos/
chmod 755 storage/
chmod 755 storage/logs/
chmod 755 storage/framework/
chmod 755 storage/framework/cache/
chmod 755 storage/framework/sessions/
chmod 755 storage/framework/views/
chmod 755 bootstrap/cache/

# Buat .htaccess untuk folder storage agar bisa diakses
cat > public/storage/.htaccess << 'EOF'
Options +Indexes
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews
    </IfModule>

    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
EOF

echo "=== Storage Setup Selesai ==="
echo "Folder yang dibuat:"
echo "- public/storage/photos (untuk foto laporan)"
echo "- storage/logs (untuk log files)"
echo "- storage/framework/ (untuk cache, sessions, views)"
echo "- bootstrap/cache (untuk bootstrap cache)"
echo ""
echo "URL foto akan: https://ck.msapt.co.id/storage/photos/[filename]"
echo "Pastikan document root subdomain menunjuk ke folder public/"
