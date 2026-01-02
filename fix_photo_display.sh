#!/bin/bash

# Fix Photo Display Issues untuk Hosting Hostinger
# Subdomain: ck.msapt.co.id

echo "=== Fix Photo Display Issues ==="

# 1. Create proper .htaccess for storage folder
echo "Creating .htaccess for storage folder..."

cat > public/storage/.htaccess << 'EOF'
Options +Indexes
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
</IfModule>

# Allow access to image files
<FilesMatch "\.(jpg|jpeg|png|gif|webp)$">
    Order Allow,Deny
    Allow from all
</FilesMatch>

# Set proper content types for images
<IfModule mod_mime.c>
    AddType image/jpeg .jpg .jpeg
    AddType image/png .png
    AddType image/gif .gif
    AddType image/webp .webp
</IfModule>

# Prevent PHP execution
<Files "*.php">
    Deny from all
</Files>

# Disable directory listing for security
Options -Indexes
EOF

# 2. Create .htaccess for photos subfolder
cat > public/storage/photos/.htaccess << 'EOF'
Options +Indexes
<IfModule mod_rewrite.c>
    RewriteEngine Off
</IfModule>

# Allow direct access to photos
<FilesMatch "\.(jpg|jpeg|png|gif|webp)$">
    Order Allow,Deny
    Allow from all
</FilesMatch>

# Set proper content types
<IfModule mod_mime.c>
    AddType image/jpeg .jpg .jpeg
    AddType image/png .png
    AddType image/gif .gif
    AddType image/webp .webp
</IfModule>

# No PHP execution
<Files "*.php">
    Deny from all
</Files>
EOF

# 3. Set proper permissions
echo "Setting folder permissions..."

chmod 755 public/
chmod 755 public/storage/
chmod 755 public/storage/photos/
chmod 644 public/storage/.htaccess
chmod 644 public/storage/photos/.htaccess

# 4. Ensure photos folder exists and is accessible
mkdir -p public/storage/photos

# 5. Test file creation
echo "Creating test file..."
echo "Test image content" > public/storage/photos/test_access.txt
chmod 644 public/storage/photos/test_access.txt

echo "=== Fix Complete ==="
echo ""
echo "Files created/modified:"
echo "- public/storage/.htaccess"
echo "- public/storage/photos/.htaccess"
echo "- public/storage/photos/test_access.txt (test file)"
echo ""
echo "Next steps:"
echo "1. Test URL: https://ck.msapt.co.id/storage/photos/test_access.txt"
echo "2. If accessible, delete test file: rm public/storage/photos/test_access.txt"
echo "3. Clear Laravel cache: php artisan config:cache"
echo "4. Test photo upload and display"
echo ""
echo "If still not working:"
echo "1. Check Hostinger file manager permissions"
echo "2. Verify document root points to public_html/ck/public"
echo "3. Check hosting error logs"
