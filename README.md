# ☕ Kopipiro - Coffee Shop Web Ordering System

Aplikasi web pemesanan kopi berbasis PHP Native dan Bootstrap 5 dengan dukungan multi-role (Admin, Owner, Kasir, Customer).

### Fitur Utama:
1. **Katalog & Pemesanan Multi-Produk:** Pelanggan dapat menjelajahi menu beserta deskripsi, rating, dan menambahkan beberapa item ke keranjang.
2. **Diskon Otomatis 10%:** Diskon 10% langsung diterapkan pada halaman checkout jika pelanggan telah terdaftar & login.
3. **Manajemen Produk & Upload Gambar (Admin):** Tambah, edit, dan hapus menu disertai pengunggahan file gambar lokal/URL.
4. **Alur Kerja Kasir:** Kasir mengelola pesanan masuk (`claim`) dan menyelesaikannya (`complete`).
5. **Dashboard & Laporan (Owner):** Visualisasi tren penjualan menggunakan Chart.js dan unduh rekap riwayat pesanan dalam format Excel.

---

### Akun Uji Coba (Semua Password: `password123`)
* **Admin:** `admin`
* **Owner:** `owner`
* **Kasir:** `kasir1`
* **Customer:** `agus`

### Cara Menjalankan:
1. Pindahkan folder proyek ke `htdocs/` (XAMPP).
2. Buat database `db_kopipiro` di phpMyAdmin dan impor file `db_kopipiro.sql`.
3. Buka `http://localhost/CoffeeShop/index.php` pada browser.
