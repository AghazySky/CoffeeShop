# ☕ Kopipiro - Web Ordering System

Aplikasi web pemesanan kopi dan menu pendukung (toast, non-kopi, dsb.) berbasis **PHP Native** dan **Bootstrap 5** dengan sistem otentikasi multi-peran (*Multi-Role Access Control*).

---

## 📌 Studi Kasus & Kebutuhan Fitur

1. **Eksplorasi Menu & Keranjang Belanja**:
   - Pelanggan dapat melihat deskripsi, harga, dan rating menu kopi maupun non-kopi.
   - Pelanggan dapat memesan beberapa produk sekaligus ke dalam keranjang.
2. **Logika Diskon 10%**:
   - Jika memesan tanpa login (sebagai *Guest*), harga normal berlaku.
   - Jika register dan login sebagai *Customer*, diskon **10%** otomatis terpotong pada halaman checkout.
3. **Manajemen Produk (Admin)**:
   - Admin dapat menambah menu baru, mengunggah gambar produk, mengedit harga/deskripsi, dan menghapus produk.
4. **Alur Kerja Kasir**:
   - Kasir hanya dapat melihat pesanan yang sedang atau akan dikelolanya (*claim* dan menyelesaikan pesanan).
5. **Dashboard & Laporan (Owner)**:
   - Owner dapat memantau seluruh transaksi pesanan yang masuk.
   - Dilengkapi visualisasi grafik omzet harian (*Chart.js*) dan fitur unduh laporan ke format Excel.

---

## 👥 Akun Uji Coba (Testing Accounts)

Semua akun pengujian di bawah ini menggunakan password: **`password123`**

| Peran (Role) | Username | Password | Deskripsi Hak Akses |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin` | `password123` | Kelola menu (CRUD + Upload Foto), Kelola Pengguna |
| **Owner** | `owner` | `password123` | Lihat semua transaksi, Grafik penjualan, Ekspor Excel |
| **Kasir** | `kasir1` | `password123` | Menangani pesanan masuk (ambil order & selesaikan) |
| **Customer** | `gazi` | `password123` | Memesan dengan diskon otomatis 10% |

---

## 🛠️ Panduan Instalasi Lokal

1. Salin atau *clone* repositori ini ke folder `htdocs` server lokal Anda (misalnya: `C:/xampp/htdocs/CoffeeShop`).
2. Jalankan server **Apache** dan **MySQL** melalui XAMPP Control Panel.
3. Buka browser dan buka phpMyAdmin (`http://localhost/phpmyadmin`).
4. Buat database baru bernama **`db_kopipiro`**.
5. Buka tab **Import**, pilih file **`db_kopipiro.sql`** dari repositori ini, lalu klik **Import**.
6. Buka aplikasi pada peramban melalui alamat:
   ```
   http://localhost/CoffeeShop/index.php
   ```

---

## 📂 Struktur Berkas Proyek

- `config.php` : Konfigurasi koneksi MySQLi ke database.
- `auth.php` : Manajemen sesi dan fungsi pengecekan hak akses tiap role.
- `navbar.php` : Navigasi dinamis menyesuaikan sesi role pengguna.
- `index.php` : Halaman katalog produk dan eksplorasi menu kopi.
- `cart.php` : Pengelolaan keranjang pemesanan belanja sementara.
- `checkout.php` : Ringkasan pemesanan dan kalkulasi diskon 10%.
- `products.php`, `product_create.php`, `product_edit.php`, `product_delete.php` : CRUD produk dan unggah foto.
- `users.php` : Manajemen data akun pengguna oleh admin.
- `orders.php` : Halaman daftar pesanan untuk kasir dan owner.
- `dashboard.php` : Visualisasi tren grafik pendapatan untuk owner.
- `export_excel.php` : Skrip pengunduh rekap transaksi ke Excel.
- `login.php`, `register.php`, `logout.php` : Autentikasi pengguna.
