<p align="center">
  <img src="public/images/002-UTI.png" alt="Logo Universitas Teknokrat Indonesia" width="120">
</p>

<h1 align="center">SILAKU</h1>
<h3 align="center">SISTEM PELAPORAN IKU</h3>
<p align="center">
  Fakultas Sastra dan Ilmu Pendidikan (FSIP) — Universitas Teknokrat Indonesia
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel 11">
  <img src="https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP 8.2+">
  <img src="https://img.shields.io/badge/TailwindCSS-3-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white" alt="TailwindCSS">
  <img src="https://img.shields.io/badge/Vite-6-646CFF?style=for-the-badge&logo=vite&logoColor=white" alt="Vite">
  <img src="https://img.shields.io/badge/MySQL-8-4479A1?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL">
</p>

---

## 📖 Tentang

**SILAKU** adalah platform manajemen data akreditasi fakultas berbasis web yang dibangun untuk Fakultas Sastra dan Ilmu Pendidikan (FSIP), Universitas Teknokrat Indonesia. Sistem ini memungkinkan pengelolaan data **Dosen**, **Mahasiswa**, **Fakultas**, dan **Alumni** secara dinamis, terintegrasi, dan terpusat dalam satu platform — dilengkapi alur persetujuan (approval), peta sebaran alumni, dan ekspor data.

> Dibangun dengan arsitektur **Dynamic Entity** — BAAK dan Kaprodi dapat membuat kategori data baru beserta field-nya secara fleksibel tanpa perlu mengubah kode sumber.

---

## ✨ Fitur Utama

### 🏗️ Dynamic Data Architecture
- **Dynamic Entity Builder** — Buat kategori data baru (tabel) secara dinamis dari UI tanpa coding
- **Custom Fields** — Mendukung tipe field: Text, Textarea, Number, Date, Select, File, Email, Phone, URL
- **Field Configuration** — Setiap field bisa dikonfigurasi: required, filterable, aggregatable, show in table
- **Root Category** — Kategori dapat diarahkan ke Dosen, Mahasiswa, atau Fakultas

### 👥 Role-Based Access Control (RBAC)

| Role | Hak Akses |
|------|-----------|
| **BAAK** | Full access — Kelola semua kategori (Dosen, Mahasiswa, Fakultas) & data langsung tanpa approval |
| **Kaprodi** | CRUD kategori Dosen/Mahasiswa/Fakultas & data Alumni — perlu persetujuan BAAK |
| **Dosen** | Tambah/edit/hapus data Dosen/Mahasiswa/Fakultas & Alumni — perlu persetujuan BAAK |
| **Pimpinan / Dekan** | Read-only — Lihat seluruh data Dosen, Mahasiswa, Fakultas, dan Alumni; unduh laporan PDF/Excel |
| **Wakil Dekan** | Read-only — Lihat seluruh data Dosen, Mahasiswa, Fakultas, dan Alumni; unduh laporan PDF/Excel |

### ✅ Sistem Persetujuan (Approval Workflow)
- Kaprodi & Dosen **tidak bisa langsung** menambah, mengedit, atau menghapus data — semua masuk antrian persetujuan BAAK
- BAAK bisa **memilih lebih dari satu permintaan** (multi-select/centang) lalu menyetujui atau menolak sekaligus (bulk approve/reject)
- Status permintaan ditampilkan di tabel: pending (kuning berputar), disetujui, atau ditolak
- Berlaku untuk: kategori Dosen/Mahasiswa/Fakultas, data Dosen/Mahasiswa/Fakultas, dan data Alumni

### 🎓 Manajemen Data Alumni
- Formulir alumni dengan field: Nama, Nama Perusahaan, Posisi, Lokasi, Program Studi
- **Input Lokasi Dinamis** dengan autocomplete real-time dari OpenStreetMap Nominatim
- Koordinat GPS (lat/lng) terdeteksi otomatis saat lokasi dipilih dari saran
- Indikator status koordinat langsung di formulir
- Role **BAAK** dapat memilih (centang) satu atau lebih alumni di tabel untuk **Hapus Terpilih**
- Tombol **Hapus Semua** data alumni (dengan konfirmasi) khusus BAAK

### 🌍 Peta Sebaran Alumni
- Peta interaktif berbasis **Leaflet.js** di dashboard
- Pilihan tampilan: **Lampung**, **Indonesia**, **Dunia** — peta berpindah otomatis dengan animasi `flyTo`
- **Klik marker → zoom in** ke lokasi secara mulus; **tutup popup (X) → zoom out** kembali ke tampilan semula
- Alumni di **lokasi yang sama** digabung dalam satu marker berwarna biru dengan daftar semua alumni di dalamnya
- Mode gelap otomatis (filter CSS hue-rotate untuk tile peta)
- Perintah geocoding: `php artisan alumni:geocode`

### 📤 Ekspor Data
- **Export Excel (.xlsx)** — Alumni, Dosen, Mahasiswa, Fakultas dengan header berformat rapi, metadata, warna baris bergantian, dan footer
- **Export PDF** — Tampilan landscape profesional untuk semua kategori dan laporan cetak resmi IKU FSIP
- Filter per Program Studi sebelum mengekspor

### 📊 Dashboard & Analytics
- Statistik real-time: Total Dosen, Mahasiswa, Fakultas, Alumni, Kategori, Program Studi
- **Donut chart** distribusi data antar Program Studi dan sebaran kategori data
- **Bar/Line/Donut chart dinamis** dari aggregatable fields tiap kategori
- Aktivitas terbaru — hanya aksi Kaprodi & Dosen yang ditampilkan (BAAK tidak di-log)
- Notifikasi bell real-time untuk permintaan yang masuk

### 📋 Log Aktivitas
- Seluruh aksi **Kaprodi** dan **Dosen** (tambah/edit/hapus/request) dicatat otomatis
- Log dapat dilihat oleh BAAK, Pimpinan, dan Wakil Dekan
- BAAK dapat menghapus log individual maupun menghapus semua log
- **BAAK tidak di-log** (aksinya langsung, tidak perlu dicatat)

### 🌍 Bilingual (ID/EN)
- Dukungan multi-bahasa penuh di Landing Page
- Language Switcher (dropdown) di Navbar
- Session persistence — pilihan bahasa diingat otomatis

### 🔐 User Management
- BAAK bisa membuat & mengelola akun Kaprodi/Dosen
- Detail profil pengguna: Nama, Email, NIP, Tipe (Tetap/Tidak Tetap), Role, Prodi
- Reset email & password untuk akun yang sudah dibuat
- Toggle aktif/nonaktif akun

### 🎨 Modern UI/UX
- Landing page responsif dengan animasi glassmorphism
- Dashboard premium dengan micro-animations & skeleton loading
- Sidebar navigasi dinamis berdasarkan data yang tersedia
- Dark mode otomatis sesuai preferensi sistem
- SweetAlert2 untuk konfirmasi & notifikasi toast
- Favicon & branding Universitas Teknokrat Indonesia

---

## 📸 Screenshots

<p align="center">
  <img src="docs/screenshots/landing-page.png" alt="Landing Page" width="100%">
  <br><em>Landing Page — Bilingual (ID/EN)</em>
</p>

<p align="center">
  <img src="docs/screenshots/dashboard.png" alt="Dashboard" width="100%">
  <br><em>Dashboard Akreditasi — Peta Sebaran Alumni & Statistik Real-time</em>
</p>

<p align="center">
  <img src="docs/screenshots/user-management.png" alt="User Management" width="100%">
  <br><em>Manajemen Pengguna</em>
</p>

---

## 🏛️ Program Studi

FSIP Universitas Teknokrat Indonesia memiliki 5 Program Studi:

| No | Program Studi | Kode |
|----|---------------|------|
| 1 | S1 Sastra Inggris | S1SS |
| 2 | S1 Pendidikan Bahasa Inggris | S1PBI |
| 3 | S1 Pendidikan Olahraga | S1PO |
| 4 | S1 Pendidikan Matematika | S1PMA |
| 5 | S2 Magister Bahasa Inggris | S2BI |

---

## 🛠️ Tech Stack

| Layer | Technology |
|-------|-----------|
| **Backend** | Laravel 11, PHP 8.2+ |
| **Frontend** | Blade Templates, TailwindCSS 3, Alpine.js |
| **Build Tool** | Vite 6 |
| **Database** | MySQL 8 |
| **Auth & RBAC** | Spatie Laravel Permission |
| **Charts** | ApexCharts |
| **Maps** | Leaflet.js + OpenStreetMap |
| **Geocoding** | Nominatim (OpenStreetMap) |
| **PDF Export** | Barryvdh Laravel DomPDF |
| **Excel Export** | PhpSpreadsheet |
| **Notifications** | SweetAlert2 |
| **Icons** | Heroicons (SVG) |
| **Font** | Inter (Google Fonts) |

---

## ⚡ Instalasi & Setup

### Prasyarat

- PHP >= 8.2
- Composer
- Node.js >= 18 & NPM
- MySQL 8

### Langkah Instalasi

```bash
# 1. Clone repository
git clone https://github.com/HafisYulianto/SILAKU-FSIP-UTI.git
cd SILAKU-FSIP-UTI

# 2. Install dependencies
composer install
npm install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi database di .env
# DB_DATABASE=nama_database
# DB_USERNAME=username_db
# DB_PASSWORD=password_db

# 5. Jalankan migrasi & seeder
php artisan migrate --seed

# 6. Buat symbolic link untuk storage
php artisan storage:link

# 7. (Opsional) Geocode data alumni yang belum memiliki koordinat
php artisan alumni:geocode

# 8. Jalankan aplikasi
php artisan serve
npm run dev
```

Akses aplikasi di: **http://127.0.0.1:8000**

---

## 🔑 Default Accounts

Setelah menjalankan `php artisan migrate --seed`, akun default tersedia untuk tiap role. Lihat file `database/seeders/DatabaseSeeder.php` untuk detail akun.

> ⚠️ **Penting:** Segera ganti password default setelah deployment ke production!

---

## 📁 Struktur Proyek

```
SILAKU-FSIP-UTI/
├── app/
│   ├── Http/Controllers/
│   │   ├── Auth/LoginController.php            # Authentication
│   │   ├── DashboardController.php             # Dashboard & statistik
│   │   ├── DynamicEntityController.php         # Kategori data CRUD
│   │   ├── DynamicRecordController.php         # Record data CRUD
│   │   ├── AlumniController.php                # Manajemen data alumni
│   │   ├── ApprovalController.php              # Alur persetujuan BAAK
│   │   ├── ActivityLogController.php           # Log aktivitas
│   │   └── UserManagementController.php        # Manajemen pengguna
│   ├── Models/
│   │   ├── DynamicEntity.php                   # Model kategori dinamis
│   │   ├── DynamicField.php                    # Model field/kolom
│   │   ├── DynamicRecord.php                   # Model record data
│   │   ├── Alumni.php                          # Model data alumni
│   │   ├── DataApprovalRequest.php             # Model permintaan persetujuan
│   │   ├── ActivityLog.php                     # Model log aktivitas
│   │   ├── ProgramStudi.php                    # Model program studi
│   │   └── User.php                            # Model pengguna
│   ├── Console/Commands/
│   │   └── GeocodeAlumni.php                   # Command geocoding alumni
│   ├── Middleware/
│   │   └── LocalizationMiddleware.php          # Middleware bahasa (i18n)
│   └── Services/
│       ├── DashboardAggregationService.php     # Service chart & aggregasi
│       ├── ExportService.php                   # Export Excel & PDF
│       └── GeocodingService.php                # Geocoding via Nominatim
├── resources/
│   ├── views/
│   │   ├── landing.blade.php                   # Landing page (bilingual)
│   │   ├── auth/login.blade.php                # Halaman login
│   │   ├── dashboard/index.blade.php           # Dashboard utama + peta
│   │   ├── entities/                           # CRUD kategori data
│   │   ├── records/                            # CRUD record data
│   │   ├── alumni/                             # CRUD data alumni
│   │   ├── approvals/                          # Halaman persetujuan BAAK
│   │   ├── activities/                         # Log aktivitas
│   │   ├── exports/                            # Template PDF export
│   │   ├── users/                              # Manajemen pengguna
│   │   └── components/                         # Blade components
│   └── css/app.css                             # Stylesheet utama
├── lang/
│   ├── en/landing.php                          # Terjemahan EN
│   └── id/landing.php                          # Terjemahan ID
├── routes/web.php                              # Route definitions
└── database/
    ├── migrations/                             # Schema database
    └── seeders/                                # Data awal
```

---

## 🔄 Arsitektur Dynamic Entity

```
┌──────────────────┐     ┌──────────────────┐     ┌──────────────────┐
│  DynamicEntity   │────▶│  DynamicField    │     │  DynamicRecord   │
│                  │     │                  │     │                  │
│  - name          │     │  - name          │     │  - data (JSON)   │
│  - root_category │     │  - type          │     │  - created_by    │
│  - description   │     │  - is_required   │     │  - program_studi │
│  - created_by    │     │  - is_filterable  │     │                  │
└──────────────────┘     │  - is_aggregatable│     └──────────────────┘
         │               └──────────────────┘              │
         └──────────────── hasMany ──────────────────────────┘
```

Setiap **Entity** (kategori) memiliki custom **Fields** (kolom). Data disimpan sebagai **Records** dengan format JSON fleksibel.

---

## 🔄 Alur Persetujuan (Approval Flow)

```
Kaprodi/Dosen                 BAAK
     │                          │
     │── Request (tambah/edit/hapus) ──▶ DataApprovalRequest (pending)
     │                          │
     │                          │── Review di halaman Persetujuan
     │                          │── Pilih satu/banyak permintaan (bulk)
     │                          │
     │◀──── Disetujui ──────────┤  Data langsung dibuat/diubah/dihapus
     │◀──── Ditolak (+ alasan) ─┘
```

---

## 👨‍💻 Dibuat Oleh

**Hafis Yulianto & M. Dava Ardana** — Mahasiswa Magang  
Fakultas Sastra dan Ilmu Pendidikan  
Universitas Teknokrat Indonesia

---

<p align="center">
  <sub>© 2026 FSIP Universitas Teknokrat Indonesia. All rights reserved.</sub>
</p>