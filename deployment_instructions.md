# Deployment Instructions untuk Catatan Kerja MSA
**Project URL:** https://ck.msapt.co.id  
**Repository:** https://github.com/aklanulh/ck.git

## Persiapan File untuk Upload

### 1. File yang Perlu Diupload
Upload semua file dan folder kecuali:
- `.git/`
- `node_modules/`
- `vendor/`
- `.env` (gunakan `env.production` sebagai template)
- `storage/logs/` (kosongkan)
- `bootstrap/cache/` (kosongkan)

### 2. Konfigurasi Environment
1. Rename `env.production` menjadi `.env`
2. Edit nilai-nilai berikut di `.env`:
   - `APP_URL`: Sudah terisi `https://ck.msapt.co.id`
   - `DB_DATABASE`: `u919556019_ck` (sudah terisi)
   - `DB_USERNAME`: `u919556019_supermsa` (sudah terisi)
   - `DB_PASSWORD`: Isi dengan password database Anda
   - `APP_KEY`: Generate baru dengan `php artisan key:generate`

### 3. Persiapan Database
1. Buat database melalui Hostinger Control Panel
2. Import file migration jika ada:
   ```sql
   -- Jalankan migration via SSH atau phpMyAdmin
   ```

### 4. Folder Permissions dan Storage Setup
Set permissions berikut via File Manager atau SSH:
```bash
# Buat folder storage yang diperlukan
mkdir -p public/storage/photos
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Set permissions
chmod 755 public/storage/
chmod 755 public/storage/photos/
chmod 755 storage/
chmod 755 storage/logs/
chmod 755 storage/framework/
chmod 755 storage/framework/cache/
chmod 755 storage/framework/sessions/
chmod 755 storage/framework/views/
chmod 755 bootstrap/cache/
```

### 5. Install Dependencies
Via SSH di Hostinger:
```bash
composer install --no-dev --optimize-autoloader
npm install && npm run build
```

### 6. Final Setup
Jalankan commands berikut:
```bash
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
# Tidak perlu php artisan storage:link karena kita menggunakan direct storage
```

## Struktur Folder di Hostinger
```
public_html/ck/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/ (ini adalah root folder web untuk subdomain)
│   ├── storage/
│   │   └── photos/    ← Foto akan disimpan di sini
│   ├── index.php
│   └── ...
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
├── artisan
└── ...
```

## Notes Penting
- Pastikan PHP version sesuai (minimum 8.1)
- Aktifkan ekstensi PHP: mbstring, openssl, pdo, tokenizer, xml
- Untuk domain ck.msapt.co.id, arahkan document root ke folder `public/`
- Clear cache setelah perubahan konfigurasi
- Database: u919556019_ck dengan user u919556019_supermsa

## Troubleshooting
- 500 Error: Check file permissions dan .env configuration
- Database Error: Verify database credentials dan connection
- Asset 404: Jalankan `npm run build` dan pastikan path storage benar
- Foto tidak muncul: Pastikan folder `public/storage/photos` ada dan permissions 755
- Foto tidak tersimpan: Check bahwa `public/storage/` writable dan path sesuai

## Storage Configuration untuk Subdomain
Untuk subdomain `ck.msapt.co.id` dengan path `public_html/ck/public`:
- Foto disimpan di: `public_html/ck/public/storage/photos/`
- URL foto: `https://ck.msapt.co.id/ck/public/storage/photos/[filename]`
- Tidak perlu symbolic link, langsung menyimpan ke public folder
- Document root subdomain harus menunjuk ke `public_html/ck/public/`

## URL Format
- **Storage Path**: `public/storage/photos/[filename]`
- **Full URL**: `https://ck.msapt.co.id/ck/public/storage/photos/[filename]`
- **Config URL**: `{APP_URL}/ck/public/storage/{path}`
