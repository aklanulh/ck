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

### 4. Folder Permissions
Set permissions berikut via File Manager atau SSH:
```bash
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
php artisan storage:link
```

## Struktur Folder di Hostinger
```
public_html/
├── app/
├── bootstrap/
├── config/
├── database/
├── public/ (ini adalah root folder web)
├── resources/
├── routes/
├── storage/
├── vendor/
├── .env
├── artisan
└── index.php
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
- Asset 404: Jalankan `php artisan storage:link` dan `npm run build`
