
# BlueChat — Real-Time Chat Application

BlueChat adalah aplikasi chat real-time berbasis Laravel + Reverb yang memungkinkan user saling kirim pesan secara langsung tanpa perlu refresh halaman.

---

## About

Project ini dibuat untuk belajar implementasi real-time chat menggunakan Laravel Reverb dan WebSocket.

---

## Fitur

- Real-time chat tanpa refresh
- Status online user
- Kirim & terima pesan langsung
- Penyimpanan pesan ke database
- Update pesan otomatis
- Tampilan chat sederhana dan responsif

---

## Teknologi

- Laravel
- Laravel Reverb
- Laravel Echo
- Pusher JS
- MySQL
- Vite
- Blade

---

## Cara Instalasi

### 1. Clone repository
```bash
git clone git@github.com:Nanjita/BlueChat.git
cd BlueChat
````

### 2. Install dependency backend

```bash
composer install
```

### 3. Install dependency frontend

```bash
npm install
```

### 4. Setup .env

```bash
cp .env.example .env
php artisan key:generate
```

### 5. Setup database (.env)

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=BlueChat
DB_USERNAME=root
DB_PASSWORD=
```

### 6. Migration

```bash
php artisan migrate --seed
```

### 7. Build frontend

```bash
npm run build
```

---

## Cara Menjalankan

Project ini butuh 2 terminal:

### Terminal 1

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

### Terminal 2

```bash
php artisan reverb:start --host=127.0.0.1 --port=8080 --debug
```

---

## Akses Aplikasi

Buka:

```
http://127.0.0.1:8000
```

Cara test:

* Buka 2 tab browser
* Login pakai user berbeda
* Kirim pesan
* Pesan langsung muncul di tab lain tanpa refresh

---

## Konfigurasi Reverb

Di file `.env`:

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

## Kalau tidak jalan

* Pastikan `reverb:start` masih jalan
* Cek port 8080 tidak dipakai
* Refresh browser
* Cek `.env` sudah benar
* Jalankan 2 terminal sekaligus

---

## Catatan

Folder `vendor` dan `node_modules` tidak diupload ke GitHub. Setelah clone jalankan:

```bash
composer install
npm install
```


