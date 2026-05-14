````md
# BlueChat — Real-Time Chat Application

Aplikasi mini chat real-time berbasis Laravel + Reverb yang memungkinkan user saling mengirim pesan tanpa refresh halaman dan pesan tersimpan ke database.

---

## Fitur

* Real-Time Chat menggunakan Laravel Reverb
* Kirim & Terima Pesan Tanpa Refresh
* Penyimpanan Pesan ke Database
* Presence Channel / Status Online User
* Tampilan Chat Modern

---

## Teknologi

* Laravel
* Laravel Reverb
* MySQL
* Blade
* Vite
* Laravel Echo
* Pusher JS

---

## Cara Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/Nanjita/BlueChat
````

### 2. Install Dependency

```bash
composer install
npm install
```

### 3. Copy File Environment

```bash
cp .env.example .env
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Buat Database

Buat database baru dengan nama:

```txt
BlueChat
```

### 6. Atur Database di File `.env`

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=BlueChat
DB_USERNAME=root
DB_PASSWORD=
```

### 7. Jalankan Migration dan Seeder

```bash
php artisan migrate --seed
```

### 8. Build Frontend Assets

```bash
npm run build
```

---

# Cara Menjalankan Project

Project wajib menjalankan 2 terminal secara bersamaan.

---

## Terminal 1 — Laravel Server

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

---

## Terminal 2 — Laravel Reverb

```bash
php artisan reverb:start --host=127.0.0.1 --port=8080 --debug
```

---

## Akses Aplikasi

Buka browser:

```txt
http://127.0.0.1:8000
```

Untuk mencoba fitur real-time:

* Buka 2 tab browser
* Kirim pesan dari tab pertama
* Pesan otomatis muncul di tab kedua tanpa refresh halaman

---

## Konfigurasi Reverb

Pastikan file `.env` memiliki konfigurasi berikut:

```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=
REVERB_APP_KEY=
REVERB_APP_SECRET=
REVERB_HOST=127.0.0.1
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

---

## Jika Real-Time / Status Online Tidak Berjalan

Pastikan:

* Terminal `reverb:start` masih aktif dan tidak tertutup
* Browser sudah di-refresh menggunakan `Ctrl + F5`
* Port `8080` tidak digunakan aplikasi lain
* File `.env` sudah sesuai konfigurasi Reverb
* Kedua terminal berjalan bersamaan

---

## Catatan

Folder `vendor` dan `node_modules` tidak disertakan di GitHub karena ukurannya besar.

Setelah clone project jalankan:

```bash
composer install
npm install
```


