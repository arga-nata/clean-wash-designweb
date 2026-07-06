# 🧼 Clean Wash DesignWeb

Sistem manajemen laundry hybrid yang menggabungkan fleksibilitas CMS Joomla untuk frontend/konten dan custom PHP portal untuk alur transaksi yang *sat-set*.

## 🚀 Tech Stack
- **Frontend:** Joomla CMS & Bootstrap
- **Backend:** Custom PHP (Procedural)
- **Database:** MySQL
- **Environment:** Docker via [DDEV](https://ddev.readthedocs.io/)
- **Deployment Target:** InfinityFree

## 🏗️ Arsitektur Hybrid
Projek ini menggunakan pendekatan hybrid untuk mengoptimalkan workflow:
- **Joomla:** Digunakan untuk manajemen konten statis, landing page, dan administrasi dasar.
- **Custom PHP Portals:** Digunakan untuk fitur kritis seperti `login`, `register`, `keranjang`, dan `order` untuk memastikan performa maksimal dan kontrol penuh atas alur data.
- **Data Sync:** Custom portal menulis data langsung ke tabel `jos_wash_orders` di database Joomla.

## 🛠️ Local Development (DDEV)

Pastikan Docker sudah terinstall, lalu jalankan command berikut:

```bash
# 1. Clone repository
git clone <url-repo>
cd clean-wash-designweb

# 2. Konfigurasi DDEV
ddev config --project-type=php --docroot=

# 3. Start environment
ddev start

# 4. Cek URL akses
ddev describe
```

## 📂 Project Structure

```text
.
├── css/                # Bootstrap & Custom Styles
│   ├── bootstrap.css
│   └── style.css
├── js/                 # Bootstrap & App Logic
│   ├── bootstrap.bundle.js
│   └── app.js
├── jpg/                # Assets Gambar
│   ├── Penerimaan.jpg
│   ├── Pengemasan.jpg
│   └── ...
├── includes/           # Modular Components
│   ├── connection.php  # Database Config
│   ├── header.php
│   ├── footer.php
│   └── admin_header.php
├── kurir/              # Courier Management
├── user/               # User Assets/Docs
├── index.php           # Main Landing Page
├── login.php           # User Authentication
├── register.php        # User Registration
├── keranjang.php       # Service Selection & Cart
├── harga.php           # Service Pricing
├── services.php        # Service Management
├── detail-order.php    # Order Details
├── riwayat-order.php    # Order History
├── save_order.php      # Order Processing Logic
├── update_status.php   # Order Status Updater
├── delete_order.php    # Order Removal
├── logout.php          # Session Destroyer
├── kontak.php          # Contact Form
├── tentang_kami.php    # Company Profile
├── galeri.php          # Gallery Page
└── paket.php           # Package Information
```
*(Folder `/admin` omitted for brevity)*

## 📝 Notes
- **Database:** Konfigurasi koneksi menggunakan host `db` saat di DDEV.
- **Deployment:** Saat deploy ke InfinityFree, ubah `db_host` menjadi host MySQL yang disediakan panel hosting.
