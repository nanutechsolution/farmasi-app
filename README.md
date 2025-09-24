# Aplikasi Manajemen Apotek (Farmasi App)

Aplikasi Manajemen Apotek adalah sebuah sistem informasi berbasis web yang modern dan lengkap, dirancang untuk membantu mengelola operasional apotek secara efisien. Dibangun dengan tumpukan teknologi Laravel 11 dan Livewire 3, aplikasi ini menawarkan pengalaman pengguna yang reaktif dan *real-time*.



## Fitur Utama ✨

Aplikasi ini dilengkapi dengan serangkaian fitur canggih yang mencakup semua kebutuhan dasar hingga profesional sebuah apotek:

* ✅ **Sistem Keamanan Berlapis**:
    * Otentikasi pengguna (Login, Logout, Register) menggunakan Laravel Breeze.
    * Hak akses berbasis peran (**Admin, Apoteker, Kasir**) menggunakan Spatie Permission.

* ✅ **Manajemen Data Inti (CRUD)**:
    * Manajemen **Obat**, lengkap dengan data harga jual, harga beli (modal), stok, barcode, dan tanggal kadaluarsa.
    * Manajemen **Supplier** untuk pencatatan pemasok obat.

* ✅ **Dashboard & Analitik**:
    * **Dashboard utama** dengan kartu statistik informatif (Total Obat, Stok Menipis, Akan Kadaluarsa).
    * **Visualisasi data** dengan grafik (chart) untuk menampilkan tren penjualan 7 hari terakhir.
    * **Laporan Keuangan** dinamis dengan filter rentang tanggal, yang secara otomatis menghitung **Total Omzet, Total Modal (HPP), dan Laba Kotor**.

* ✅ **Sistem Operasional & Inventaris**:
    * **Modul Transaksi Penjualan (POS)** dengan keranjang belanja *real-time*.
    * **Integrasi Barcode Scanner** untuk mempercepat proses penjualan.
    * **Modul Pembelian Stok** yang otomatis memperbarui stok dan menghitung ulang **harga modal rata-rata (Moving Average Cost)**.

* ✅ **Fitur Profesional**:
    * **Cetak Struk Transaksi ke PDF** dengan URL berbasis nomor invoice.
    * **Log Aktivitas (Audit Trail)** untuk melacak semua perubahan data penting yang dilakukan oleh pengguna.
    * **Manajemen User** oleh Admin untuk menambah, mengedit, dan menetapkan peran pengguna.
    * **UI/UX Modern** dengan navigasi SPA-like (`wire:navigate`), indikator *loading*, tabel yang bisa diurutkan, dan notifikasi interaktif.

## Tumpukan Teknologi 🛠️
* **Backend**: Laravel 11
* **Frontend**: Livewire 3, Alpine.js, Tailwind CSS
* **Database**: MySQL
* **Paket Utama**:
    * `laravel/breeze`
    * `spatie/laravel-permission`
    * `spatie/laravel-activitylog`
    * `barryvdh/laravel-dompdf`
    * `pestphp/pest`
    * `chart.js`

---

## Panduan Instalasi Lokal

Untuk menjalankan proyek ini di lingkungan pengembangan lokal, ikuti langkah-langkah berikut:

1.  **Clone repositori ini:**
    ```bash
    git clone [https://github.com/nanutechsolution/farmasi-app.git]
    cd [farmasi-app]
    ```

2.  **Instal dependensi Composer:**
    ```bash
    composer install
    ```

3.  **Siapkan file environment:**
    ```bash
    cp .env.example .env
    ```

4.  **Generate kunci aplikasi:**
    ```bash
    php artisan key:generate
    ```

5.  **Konfigurasi database** Anda di dalam file `.env`, lalu buat database-nya.

6.  **Jalankan migrasi dan seeder** untuk membuat semua tabel dan mengisi data awal:
    ```bash
    php artisan migrate:fresh --seed
    ```

7.  **Instal dependensi NPM dan compile aset:**
    ```bash
    npm install
    npm run build
    ```

8.  **Jalankan server pengembangan:**
    ```bash
    # Di terminal 1
    php artisan serve

    # Di terminal 2
    npm run dev
    ```

Aplikasi sekarang berjalan di `http://127.0.0.1:8000`.

---

## Akun Default
Anda bisa login menggunakan akun default yang sudah dibuat oleh seeder:

* **Role**: Admin
* **Email**: `admin@farmasi.com`
* **Password**: `password`

* **Role**: Apoteker
* **Email**: `apoteker@farmasi.com`
* **Password**: `password`

* **Role**: Kasir
* **Email**: `kasir@farmasi.com`
* **Password**: `password`

## Menjalankan Tes
Untuk menjalankan tes otomatis, gunakan perintah berikut:
```bash
# Untuk Windows
vendor\bin\pest

# Untuk Linux/MacOS
./vendor/bin/pest
