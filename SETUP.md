# Panduan Setup Project Lokal

Ikuti panduan berikut untuk menjalankan template project backend ini di komputer lokal Anda.

---

## 1. Persyaratan System (Prerequisites)
Sebelum memulai, pastikan komputer Anda sudah terinstal:
* **PHP**: Versi `>= 7.3` (Disarankan PHP 7.4 / 8.0) dengan ekstensi `pdo_pgsql`, `pgsql`, `gd`, `imagick`, `zip`.
* **Composer**: Dependency Manager untuk PHP.
* **PostgreSQL**: Versi `>= 10` (Database Server).

---

## 2. Langkah-Langkah Setup

### Langkah 1: Clone Repository
Clone project ini ke folder server lokal Anda (misal di Laragon `www/` atau Apache `htdocs/`).

### Langkah 2: Install Dependensi Composer
Buka terminal di root directory project, lalu jalankan:
```bash
composer install
```

### Langkah 3: Konfigurasi Environment (`.env`)
Salin file contoh `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```
Buka file `.env` baru tersebut, lalu sesuaikan konfigurasi database PostgreSQL Anda:
```env
DB_HOST=localhost
DB_PORT=5432
DB_NAME=db_template
DB_USER=postgres
DB_PASSWORD=
```

### Langkah 4: Setup Database
1. Buka PostgreSQL client Anda (pgAdmin, DBeaver, dll).
2. Buat database baru dengan nama `db_template`.
3. Import schema dan data dari file dump SQL yang berada di **`DB/template_db.sql`** ke database `db_template` tersebut.

---

## 3. Menjalankan Server Lokal
Anda bisa menggunakan server bawaan Yii dengan menjalankan perintah:
```bash
php yii serve
```
Akses server melalui browser di alamat:
`http://localhost:8080`

### Akun Login Default (Admin):
* **Username**: `admin`
* **Password**: `admin123`
