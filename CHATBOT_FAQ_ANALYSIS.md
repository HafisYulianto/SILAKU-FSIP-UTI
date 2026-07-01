# Laporan Analisis Website & Spesifikasi Teknis Chatbot FAQ
## Proyek: SILAKU (Sistem Pelaporan IKU) — Fakultas Sastra dan Ilmu Pendidikan (FSIP) Universitas Teknokrat Indonesia

Laporan ini disusun sebagai panduan teknis komprehensif untuk merancang dan mengintegrasikan Chatbot FAQ sederhana berbasis kata kunci (keyword-based) tanpa menggunakan API pihak ketiga, kecerdasan buatan (AI) eksternal, atau model bahasa besar (LLM). Integrasi akan dilakukan secara murni pada sisi klien (client-side) menggunakan ekosistem frontend yang sudah ada, yaitu **Blade Templates, TailwindCSS 3, dan Alpine.js**.

---

## 1. Stack Teknologi & Struktur Aplikasi

Berdasarkan analisis file konfigurasi (`composer.json`, `package.json`, dan file lingkungan `.env`), sistem pelaporan IKU SILAKU memiliki stack teknologi sebagai berikut:

### Stack Teknologi Utama
*   **Backend Framework:** Laravel 11.31 (PHP 8.2+)
*   **Frontend Library/Framework:** Blade Templates, Alpine.js 3.x, TailwindCSS 3.4.19
*   **Build Tool:** Vite 6.0.11 + PostCSS 8.5.10
*   **Database:** MySQL 8
*   **Manajemen Peran & Akses (RBAC):** Spatie Laravel Permission 6.25
*   **Visualisasi Data & Grafik:** ApexCharts
*   **Peta Interaktif:** Leaflet.js + OpenStreetMap
*   **Layanan Geocoding:** Nominatim OpenStreetMap API
*   **Ekspor Dokumen:** Barryvdh Laravel DomPDF (PDF) & PhpSpreadsheet 5.7 / Maatwebsite Excel 1.1 (Excel)
*   **Sistem Notifikasi & Alert:** SweetAlert2 11 (Kustomisasi Premium Glassmorphism)

---

## 2. Struktur Navigasi & Nama Rute (Route Names)

Rute-rute utama yang didefinisikan dalam `routes/web.php` dan akan digunakan sebagai tautan navigasi chatbot adalah sebagai berikut:

### A. Halaman Publik & Autentikasi
*   `route('landing')` (GET `/`): Halaman utama landing page yang responsif dan bilingual.
*   `route('login')` (GET `/login`): Halaman masuk sistem untuk semua pengguna.
*   `route('login.submit')` (POST `/login`): Aksi untuk mengirim formulir autentikasi.
*   `route('logout')` (POST `/logout`): Aksi untuk keluar dari sistem.
*   `route('lang.switch', ['locale' => 'id|en'])` (GET `/lang/{locale}`): Mengubah bahasa sistem (Indonesian/English) dan menyimpannya di dalam sesi.

### B. Halaman Dashboard & Utama (Semua Peran Terautentikasi)
*   `route('dashboard')` (GET `/dashboard`): Dashboard utama yang memuat peta sebaran alumni, statistik ringkas, dan grafik distribusi data program studi.

### C. Manajemen Kategori Data / Entitas Dinamis (Akses: BAAK & Kaprodi)
*   `route('entities.index')` (GET `/entities`): Halaman daftar kategori data yang aktif dan pengajuan yang tertunda.
*   `route('entities.create')` (GET `/entities/create`): Halaman pembangun kategori data dinamis (Entity Builder).
*   `route('entities.store')` (POST `/entities`): Aksi menyimpan kategori data baru.
*   `route('entities.show', ['entity' => $id])` (GET `/entities/{entity}`): Halaman detail struktur kategori data dan daftar rekaman data di dalamnya.
*   `route('entities.view', ['entity' => $id])` (GET `/entities/{entity}/view`): Halaman penjelajahan data rekaman (records) untuk kategori tertentu.
*   `route('entities.edit', ['entity' => $id])` (GET `/entities/{entity}/edit`): Halaman pengeditan informasi kategori data.
*   `route('entities.delete', ['entity' => $id])` (DELETE `/entities/{entity}/delete`): Mengajukan atau melakukan penghapusan kategori data.

### D. Pengisian & Manajemen Data Rekaman (Akses: BAAK, Kaprodi, & Dosen)
*   `route('records.create', ['entity' => $id])` (GET `/entities/{entity}/records/create`): Halaman formulir pengisian data rekaman baru.
*   `route('records.store', ['entity' => $id])` (POST `/entities/{entity}/records`): Aksi menyimpan data rekaman baru.
*   `route('records.show', ['entity' => $id, 'record' => $recordId])` (GET `/entities/{entity}/records/{record}`): Halaman detail rekaman data dinamis.
*   `route('records.edit', ['entity' => $id, 'record' => $recordId])` (GET `/entities/{entity}/records/{record}/edit`): Halaman formulir edit data rekaman.
*   `route('records.update', ['entity' => $id, 'record' => $recordId])` (PUT `/entities/{entity}/records/{record}`): Aksi memperbarui data rekaman.
*   `route('records.destroy', ['entity' => $id, 'record' => $recordId])` (DELETE `/entities/{entity}/records/{record}`): Mengajukan atau menghapus data rekaman.

### E. Manajemen Pengguna & Persetujuan (Akses: BAAK)
*   `route('users.index')` (GET `/users`): Halaman daftar pengguna (Kaprodi/Dosen/Wakil Dekan).
*   `route('users.create')` (GET `/users/create`): Halaman pembuatan akun pengguna baru.
*   `route('users.store')` (POST `/users`): Aksi menyimpan akun baru.
*   `route('users.edit', ['user' => $id])` (GET `/users/{user}/edit`): Halaman edit akun pengguna.
*   `route('users.update', ['user' => $id])` (PUT `/users/{user}`): Aksi memperbarui akun pengguna.
*   `route('users.toggle-active', ['user' => $id])` (PATCH `/users/{user}/toggle-active`): Mengaktifkan atau menonaktifkan status akun pengguna.
*   `route('approvals.index')` (GET `/approvals`): Pusat persetujuan (kategori, data alumni, dan data rekaman).
*   `route('approvals.approve', ['entity' => $id])` (POST `/approvals/{entity}/approve`): Aksi persetujuan kategori data.
*   `route('approvals.reject', ['entity' => $id])` (POST `/approvals/{entity}/reject`): Aksi penolakan kategori data dengan menyertakan alasan.
*   `route('approvals.data.bulk-approve')` (POST `/approvals/data/bulk-approve`): Aksi persetujuan massal untuk data alumni/rekaman yang diajukan.
*   `route('approvals.data.bulk-reject')` (POST `/approvals/data/bulk-reject`): Aksi penolakan massal untuk data alumni/rekaman yang diajukan.

### F. Manajemen Data Alumni & Ekspor
*   `route('alumni.index')` (GET `/alumni`): Halaman penjelajahan dan pencarian data alumni.
*   `route('alumni.create')` (GET `/alumni/create/form`): Halaman formulir tambah data alumni.
*   `route('alumni.store')` (POST `/alumni`): Aksi menyimpan data alumni baru.
*   `route('alumni.edit', ['alumni' => $id])` (GET `/alumni/{alumni}/edit`): Halaman formulir edit data alumni.
*   `route('alumni.update', ['alumni' => $id])` (PUT `/alumni/{alumni}`): Aksi memperbarui data alumni.
*   `route('alumni.destroy', ['alumni' => $id])` (DELETE `/alumni/{alumni}`): Aksi mengajukan atau menghapus data alumni.
*   `route('alumni.bulk-destroy')` (POST `/alumni/bulk-destroy`): Aksi hapus data alumni terpilih (hanya BAAK).
*   `route('alumni.destroy-all')` (POST `/alumni/destroy-all`): Aksi hapus seluruh data alumni (hanya BAAK).
*   `route('alumni.export-excel')` (GET `/alumni/export/excel`): Mengunduh laporan alumni dalam format Excel (.xlsx).
*   `route('alumni.export-pdf')` (GET `/alumni/export/pdf`): Mengunduh laporan alumni dalam format PDF (Landscape).
*   `route('entities.export-excel', ['entity' => $id])` (GET `/entities/{entity}/export-excel`): Mengunduh data rekaman kategori dalam format Excel.
*   `route('entities.export-pdf', ['entity' => $id])` (GET `/entities/{entity}/export-pdf`): Mengunduh data rekaman kategori dalam format PDF.

---

## 3. Hak Akses & Akun Pengguna (RBAC Analysis)

Sistem menggunakan kontrol akses berbasis peran (RBAC). Setiap peran memiliki batasan hak akses yang berbeda:

### Hak Akses Peran
1.  **BAAK (Super Admin):**
    *   Memiliki kontrol penuh terhadap seluruh sistem.
    *   Melakukan CRUD kategori data dan data rekaman secara langsung tanpa memerlukan persetujuan.
    *   Melakukan CRUD data alumni dan menghapus secara massal (`bulkDestroy` / `destroyAll`).
    *   Mengelola akun pengguna (Kaprodi, Dosen, Wakil Dekan) dan mengaktifkan/menonaktifkan akun.
    *   Menerima notifikasi bell real-time, meninjau, menyetujui, atau menolak pengajuan data/kategori.
    *   Aktivitas BAAK tidak dicatat di log aktivitas untuk efisiensi.
2.  **Kaprodi:**
    *   Melakukan CRUD kategori data untuk bidang "Dosen" atau "Mahasiswa". Namun, proses tambah dan hapus kategori harus diajukan sebagai draf (`pending` atau `pending_delete`) yang memerlukan persetujuan BAAK.
    *   Melakukan pengisian, pengeditan, dan penghapusan data rekaman kategori dan alumni. Namun, aksi **hapus** akan dialihkan menjadi pengajuan persetujuan (`DataApprovalRequest`) kepada BAAK.
    *   Semua tindakan dicatat dalam log aktivitas secara otomatis.
3.  **Dosen:**
    *   Melakukan pengisian dan pengeditan data rekaman kategori dan alumni.
    *   Tidak dapat mengelola kategori data (Dynamic Entity Builder).
    *   Aksi **hapus** data rekaman dan data alumni akan masuk antrian persetujuan BAAK.
    *   Semua tindakan dicatat dalam log aktivitas secara otomatis.
4.  **Pimpinan / Dekan (Read-Only):**
    *   Hanya dapat membaca data (Read-Only).
    *   Dapat mengakses dashboard statistik, grafik dinamis, peta sebaran alumni, dan mengunduh laporan PDF/Excel.
    *   Dapat melihat log aktivitas sistem.
    *   Aksi tidak dicatat di log aktivitas.
5.  **Wakil Dekan (Read-Only):**
    *   Memiliki hak akses yang sama persis dengan Pimpinan/Dekan (Read-Only).

### Akun Default (Seeded)
Berdasarkan `DefaultUserSeeder.php`, akun bawaan sistem adalah:
*   **BAAK:** `aminudin@teknokrat.ac.id` | Password: `amin123F51p@UTI` | NIP: `7700011292` (NITK)
*   **Pimpinan (Dekan):** `hery@teknokrat.ac.id` | Password: `heri123F51p@UTI` | NIP: `023080701` (NIK)
*   **Kaprodi:** `Belum dapat dipastikan` (Dibuat secara dinamis oleh BAAK melalui UI).
*   **Dosen:** `Belum dapat dipastikan` (Dibuat secara dinamis oleh BAAK melalui UI).
*   **Wakil Dekan:** `Belum dapat dipastikan` (Dibuat secara dinamis oleh BAAK melalui UI).

---

## 4. Analisis Formulir, Validasi, dan Error Handling

Penerapan chatbot FAQ memerlukan pemahaman tentang bagaimana input divalidasi dan bagaimana error ditampilkan ke pengguna.

### A. Formulir Login (`auth/login.blade.php`)
*   **Tipe Input:** `email` (email), `password` (password), `remember` (checkbox).
*   **Input Wajib:** Email dan Password.
*   **Validasi Backend:** Email harus berformat email yang valid. Password wajib diisi.
*   **Pesan Error & Respon:**
    *   Jika email/password salah: Muncul SweetAlert2 modal dengan pesan `"Email atau password salah."`
    *   Jika akun dinonaktifkan: Muncul SweetAlert2 modal dengan pesan `"Akun Anda telah dinonaktifkan. Hubungi BAAK."`
    *   Jika input kosong: Validasi HTML browser memblokir submit, atau Laravel melempar error validasi yang memicu SweetAlert2 modal bertuliskan `"Terdapat kesalahan pada input Anda"`.
    *   Jika berhasil: Dialihkan ke rute `/dashboard`.

### B. Pembuatan Akun Baru (`users/create.blade.php`)
*   **Tipe Input:** `name` (text), `email` (email), `nip_type` (select: NIP, NIK, NITK), `nip` (text), `password` (password), `password_confirmation` (password), `role` (select: Kaprodi, Dosen, Wakil Dekan), `program_studi_id` (select).
*   **Input Wajib:** Nama, Email, Password, Konfirmasi Password, Role.
*   **Validasi Backend:**
    *   `email` harus berformat email dan unik di tabel `users`.
    *   `password` minimal 8 karakter dan harus cocok dengan `password_confirmation`.
    *   `nip` harus unik dan berupa string maksimal 30 karakter.
    *   `role` harus berupa Kaprodi, Dosen, atau Wakil Dekan.
    *   `program_studi_id` harus ada di tabel `program_studi`.
*   **Pesan Error & Respon:**
    *   Jika gagal: Kembali ke halaman pembuatan dengan detail pesan kesalahan di bawah field terkait atau SweetAlert2 berbunyi `"Terdapat kesalahan pada input Anda"`.
    *   Jika sukses: Dialihkan ke `users.index` dengan notifikasi SweetAlert2 sukses: `"Akun [Nama] dengan role [Role] berhasil dibuat."`

### C. Tambah Alumni (`alumni/create.blade.php`)
*   **Tipe Input:** `nama` (text), `nama_perusahaan` (text), `posisi` (text), `lokasi` (text), `program_studi_id` (select), `lat` (hidden), `lng` (hidden).
*   **Input Wajib:** Nama Lengkap, Nama Perusahaan, Posisi, Lokasi.
*   **Validasi Backend & Geocoding:**
    *   `nama`, `nama_perusahaan`, `posisi`, `lokasi` wajib diisi berupa string maksimal 255 karakter.
    *   `lat` dan `lng` opsional tetapi harus numerik jika diisi.
    *   *Autocomplete Geocoding:* Saat pengguna mengetik di input `lokasi`, JavaScript memicu AJAX ke `/alumni/geocode/suggest?q=...`. Pengguna harus memilih salah satu lokasi agar koordinat `lat` & `lng` terisi otomatis. Status koordinat diindikasikan dengan badge hijau `"📍 Koordinat otomatis terdeteksi"` atau abu-abu `"❓ Koordinat belum dideteksi"`.
*   **Pesan Error & Respon:**
    *   Jika sukses: Menyimpan data langsung ke tabel `alumnis` (untuk semua role) dan mengarahkan ke `/alumni` dengan pesan sukses `"Data alumni [Nama] berhasil ditambahkan."`

### D. Dynamic Entity Builder (`entities/create.blade.php`)
*   **Tipe Input:** `name` (text), `root_category` (select: dosen, mahasiswa), `description` (textarea), array fields: `fields[*][name]` (text), `fields[*][type]` (select), opsi-opsi field (`is_required`, `show_in_table`, `is_filterable`, `is_aggregatable`).
*   **Input Wajib:** Nama Kategori, Kategori Utama, minimal 1 Field dengan Nama & Tipe.
*   **Validasi Backend:** `root_category` harus 'dosen' atau 'mahasiswa'. Tipe data field harus sesuai pilihan valid (`text`, `textarea`, `number`, `date`, `select`, `file`, `email`, `phone`, `url`).
*   **Respon:**
    *   Jika dibuat oleh BAAK: Disimpan dengan status `approved` dan dialihkan ke detail kategori.
    *   Jika dibuat oleh Kaprodi: Disimpan dengan status `pending` dan dialihkan ke daftar kategori dengan notifikasi `"Kategori data [Nama] berhasil diajukan dan menunggu persetujuan Admin BAAK."`

### E. Dynamic Record Form (`records/create.blade.php`)
*   **Tipe Input:** Berubah secara dinamis berdasarkan kolom yang didefinisikan pada kategori tersebut. Mendukung input text, number, date, select, dan file upload.
*   **Input Wajib:** Ditentukan secara dinamis berdasarkan properti `is_required` dari masing-masing field di database.
*   **Validasi Backend:** Dihasilkan secara dinamis di `DynamicRecordController` berdasarkan tipe data field. Jika tipe file, dibatasi ukuran maksimal 10 MB (`max:10240`).

---

## 5. Rencana Integrasi Chatbot FAQ

Chatbot dirancang agar terintegrasi secara mulus ke dalam website tanpa merusak desain premium yang sudah ada.

### A. Lokasi File Integrasi
Chatbot FAQ akan disematkan di dalam master layout aplikasi:
*   `resources/views/components/layouts/app.blade.php` (Untuk pengguna yang sudah login di dashboard).
*   `resources/views/landing.blade.php` atau `resources/views/components/layouts/guest.blade.php` (Optional, untuk halaman publik jika ingin membantu pengguna yang kesulitan masuk).

### B. Desain UI Chatbot (Premium Glassmorphism & Theme Matching)
Tampilan chatbot harus menyatu dengan tema premium SILAKU (Emerald & Teal):
*   **Tombol Pemicu (Trigger Button):** Tombol melayang (floating button) di pojok kanan bawah (`fixed bottom-6 right-6 z-50`). Menggunakan gradasi warna emerald-teal (`bg-gradient-to-r from-emerald-500 to-teal-500`), ikon pesan SVG, efek hover pembesaran micro-animation (`hover:scale-110 active:scale-95`), dan efek bayangan berpendar (`shadow-emerald-500/30`).
*   **Panel Chat (Chat Window):** Panel melayang berukuran tinggi tetap (`h-[480px] w-80 sm:w-96`) dengan sudut melengkung asimetris premium seperti pada kartu login.
*   **Desain Header:** Gradasi emerald-teal dengan foto profil avatar chatbot bertema akademis (misal: "SILA - Asisten Virtual"), status hijau berdenyut ("Online"), dan tombol tutup (X).
*   **Desain Area Chat:** Latar belakang glassmorphism (`backdrop-blur-lg border border-white/10 dark:border-gray-800/30 bg-white/95 dark:bg-gray-900/95`) dengan area percakapan scrollable. Balon pesan pengguna berwarna emerald/teal, sedangkan balon pesan bot berwarna abu-abu muda (mode terang) atau abu-abu gelap (mode gelap).
*   **Tombol Rekomendasi (Quick Replies):** Tombol mini melengkung di bagian bawah area chat yang dapat diklik langsung oleh pengguna untuk memicu pertanyaan instan.
*   **Area Input:** Kolom teks input premium dengan tombol kirim bertipe ikon pesawat kertas.

### C. Manajemen Multi-bahasa (ID/EN)
Sistem memiliki fitur bilingual. Chatbot FAQ akan menyesuaikan bahasa berdasarkan preferensi sesi locale aktif:
*   Membaca atribut bahasa dokumen: `const locale = document.documentElement.lang || 'id';`
*   Menyediakan dataset pertanyaan dan jawaban dalam dua bahasa (Indonesian & English). Jika halaman sedang aktif dalam mode bahasa Inggris, chatbot akan menyajikan pertanyaan populer dan memberikan respon dalam bahasa Inggris.

### D. Persistensi Chat History
Agar percakapan tidak hilang saat pengguna berpindah halaman (routing tradisional Laravel memicu reload halaman penuh):
*   Riwayat chat (array berisi objek pesan) akan disimpan di `localStorage` browser dengan kunci `silaku_chat_history`.
*   Status panel chat (buka/tutup) juga dapat disimpan di `localStorage` agar tetap dalam keadaan terbuka jika pengguna berpindah halaman saat sedang berkonsultasi.

---

## 6. Logika Sistem Pencocokan Kata Kunci (Keyword Matching Logic)

Pencocokan kata kunci akan diimplementasikan menggunakan JavaScript murni yang dibungkus di dalam komponen Alpine.js.

### Alur Algoritma Pencocokan
1.  **Normalisasi Teks Input:**
    *   Ubah semua huruf menjadi kecil (lowercase).
    *   Hapus karakter tanda baca (seperti `?`, `!`, `.`, `,`, `-`, `/`).
    *   Trim spasi berlebih.
    *   *Contoh:* `"Bagaimana cara ekspor ke excel?"` menjadi `["bagaimana", "cara", "ekspor", "ke", "excel"]`.

2.  **Perhitungan Skor Kecocokan (Score-based Matching):**
    *   Setiap pertanyaan FAQ memiliki daftar kata kunci kunci (keywords) berbobot.
    *   Sistem melakukan iterasi ke seluruh pertanyaan dan menghitung berapa banyak kata kunci dari daftar pertanyaan yang terkandung dalam token input pengguna.
    *   Menggunakan pembobotan sederhana: Setiap kecocokan kata kunci menambah skor `+1`. Jika ada kata kunci utama yang sangat spesifik (misal: `excel`, `pdf`, `geocoding`, `aminudin`), bobotnya bisa diatur menjadi `+2`.

3.  **Pengambilan Keputusan:**
    *   Tentukan nilai ambang batas kecocokan minimum (threshold), misal: `skor >= 1`.
    *   Pilih pertanyaan dengan skor kecocokan tertinggi.
    *   Jika skor tertinggi di bawah ambang batas, bot akan memberikan respon **fallback** (tidak mengenali pertanyaan).

### Rancangan Kode Logika Alpine.js
```javascript
function chatbotLogic() {
    return {
        isOpen: localStorage.getItem('silaku_chat_open') === 'true',
        messages: JSON.parse(localStorage.getItem('silaku_chat_history')) || [
            { sender: 'bot', text: 'Halo! Saya SILA, asisten virtual SILAKU. Ada yang bisa saya bantu?', time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'}) }
        ],
        userInput: '',
        locale: document.documentElement.lang || 'id',
        
        // Dataset FAQ
        faqData: {
            id: [
                {
                    id: 'login',
                    question: 'Bagaimana cara masuk/login ke dalam sistem?',
                    keywords: ['login', 'masuk', 'akun', 'password', 'email', 'credential', 'akses', 'portal'],
                    answer: 'Untuk masuk ke SILAKU, silakan akses halaman login. Masukkan email dan password terdaftar Anda. Jika belum memiliki akun, hubungi Admin BAAK untuk dibuatkan akun.',
                    links: [{ label: 'Buka Halaman Login', url: '/login', route_name: "login" }]
                },
                {
                    id: 'default_accounts',
                    question: 'Apa saja akun default bawaan sistem?',
                    keywords: ['akun default', 'default', 'seeder', 'aminudin', 'hery', 'dekan', 'admin', 'password default', 'username default'],
                    answer: 'Akun default setelah instalasi adalah:\n1. BAAK (Admin): aminudin@teknokrat.ac.id (password: amin123F51p@UTI)\n2. Pimpinan (Dekan): hery@teknokrat.ac.id (password: heri123F51p@UTI)\nUntuk Kaprodi dan Dosen harus dibuat manual oleh BAAK.',
                    links: [{ label: 'Halaman Login', url: '/login', route_name: "login" }]
                },
                {
                    id: 'roles',
                    question: 'Apa perbedaan peran/role BAAK, Kaprodi, Dosen, dan Pimpinan?',
                    keywords: ['role', 'peran', 'akses', 'hak', 'baak', 'kaprodi', 'dosen', 'pimpinan', 'wakil dekan'],
                    answer: 'BAAK memiliki akses penuh untuk kelola data & user serta persetujuan. Kaprodi & Dosen dapat menginput data tetapi perlu persetujuan BAAK untuk penghapusan (Kaprodi juga bisa buat kategori). Pimpinan & Wakil Dekan memiliki hak read-only untuk melihat grafik & peta serta ekspor laporan.',
                    links: []
                },
                {
                    id: 'approval',
                    question: 'Bagaimana alur persetujuan (approval) data?',
                    keywords: ['approval', 'persetujuan', 'pending', 'antrean', 'menunggu', 'ditolak', 'disetujui', 'bulk', 'massal'],
                    answer: 'Setiap kali Kaprodi atau Dosen mengajukan penghapusan data (atau Kaprodi membuat kategori baru), permintaan masuk ke antrean persetujuan. Admin BAAK akan meninjau di menu Persetujuan untuk menyetujui atau menolak (bisa bulk/sekaligus).',
                    links: [{ label: 'Menu Persetujuan BAAK', url: '/approvals', route_name: "approvals.index" }]
                },
                {
                    id: 'alumni_map',
                    question: 'Bagaimana cara kerja peta sebaran alumni dan geocoding?',
                    keywords: ['peta', 'sebaran', 'alumni', 'lokasi', 'leaflet', 'map', 'koordinat', 'gps', 'nominatim', 'geocode'],
                    answer: 'Peta di dashboard menggunakan Leaflet.js. Saat menambahkan data alumni, isi lokasi dan pilih rekomendasi dari OpenStreetMap Nominatim agar koordinat terisi otomatis. Anda juga bisa menjalankan perintah geocoding di terminal: php artisan alumni:geocode.',
                    links: [{ label: 'Lihat Data Alumni', url: '/alumni', route_name: "alumni.index" }]
                },
                {
                    id: 'export',
                    question: 'Bagaimana cara ekspor data laporan ke Excel atau PDF?',
                    keywords: ['ekspor', 'export', 'excel', 'xlsx', 'pdf', 'download', 'unduh', 'cetak', 'laporan'],
                    answer: 'Anda dapat mengunduh laporan di halaman Data Alumni atau halaman penjelajahan Kategori Data. Pilih tombol "Export Excel" (.xlsx) atau "Export PDF" (landscape). Anda dapat memfilter data berdasarkan Program Studi sebelum melakukan ekspor.',
                    links: [{ label: 'Data Alumni', url: '/alumni', route_name: "alumni.index" }]
                },
                {
                    id: 'entity_builder',
                    question: 'Bagaimana cara membuat kategori data dinamis?',
                    keywords: ['kategori', 'tambah kategori', 'buat kategori', 'dynamic entity', 'builder', 'field', 'kolom'],
                    answer: 'BAAK dan Kaprodi dapat membuat kategori data baru melalui menu "Buat Kategori Baru". Anda dapat mendefinisikan kolom data dengan tipe teks, angka, tanggal, file upload, dll. Pengajuan oleh Kaprodi memerlukan persetujuan BAAK.',
                    links: [{ label: 'Buat Kategori Baru', url: '/entities/create', route_name: "entities.create" }]
                }
            ],
            en: [
                {
                    id: 'login',
                    question: 'How do I login to the system?',
                    keywords: ['login', 'sign in', 'account', 'password', 'email', 'credential', 'access', 'portal'],
                    answer: 'To access SILAKU, please go to the login page. Enter your registered email and password. If you do not have an account, contact the BAAK Admin to create one for you.',
                    links: [{ label: 'Open Login Page', url: '/login', route_name: "login" }]
                },
                {
                    id: 'default_accounts',
                    question: 'What are the default seeded accounts?',
                    keywords: ['default account', 'default', 'seeder', 'aminudin', 'hery', 'dekan', 'admin', 'default password', 'default username'],
                    answer: 'The default accounts after installation are:\n1. BAAK (Admin): aminudin@teknokrat.ac.id (password: amin123F51p@UTI)\n2. Pimpinan (Dekan): hery@teknokrat.ac.id (password: heri123F51p@UTI)\nKaprodi and Dosen accounts must be created manually by BAAK.',
                    links: [{ label: 'Login Page', url: '/login', route_name: "login" }]
                },
                {
                    id: 'roles',
                    question: 'What are the differences between BAAK, Kaprodi, Dosen, and Pimpinan?',
                    keywords: ['role', 'permission', 'access', 'right', 'baak', 'kaprodi', 'dosen', 'pimpinan', 'vice dean'],
                    answer: 'BAAK has full access to manage data, users, and approvals. Kaprodi & Dosen can input data but need BAAK approval to delete (Kaprodi can also build categories). Pimpinan & Vice Dean have read-only access to view charts, map, and export reports.',
                    links: []
                },
                {
                    id: 'approval',
                    question: 'How does the data approval workflow work?',
                    keywords: ['approval', 'pending', 'queue', 'waiting', 'rejected', 'approved', 'bulk', 'mass'],
                    answer: 'Whenever a Kaprodi or Dosen requests to delete data (or Kaprodi creates a category), it goes into the approval queue. The BAAK Admin will review and approve or reject it (bulk operations are supported) in the Approvals menu.',
                    links: [{ label: 'BAAK Approvals Menu', url: '/approvals', route_name: "approvals.index" }]
                },
                {
                    id: 'alumni_map',
                    question: 'How does the alumni distribution map and geocoding work?',
                    keywords: ['map', 'distribution', 'alumni', 'location', 'leaflet', 'coordinates', 'gps', 'nominatim', 'geocode'],
                    answer: 'The dashboard map is powered by Leaflet.js. When inserting alumni data, type the location and select a suggestion from OpenStreetMap Nominatim so coordinates populate automatically. You can also run php artisan alumni:geocode in the terminal.',
                    links: [{ label: 'View Alumni Data', url: '/alumni', route_name: "alumni.index" }]
                },
                {
                    id: 'export',
                    question: 'How do I export report data to Excel or PDF?',
                    keywords: ['export', 'excel', 'xlsx', 'pdf', 'download', 'print', 'report'],
                    answer: 'You can download reports from the Alumni Data or Kategori Data page. Select "Export Excel" (.xlsx) or "Export PDF" (landscape). You can filter the data by Study Program (Program Studi) before exporting.',
                    links: [{ label: 'Alumni Data', url: '/alumni', route_name: "alumni.index" }]
                },
                {
                    id: 'entity_builder',
                    question: 'How do I create a dynamic data category?',
                    keywords: ['category', 'new category', 'create category', 'dynamic entity', 'builder', 'field', 'column'],
                    answer: 'BAAK and Kaprodi can create new categories via the "Buat Kategori Baru" menu. You can define fields of type text, number, date, file upload, etc. Requests made by Kaprodi require BAAK approval.',
                    links: [{ label: 'Create New Category', url: '/entities/create', route_name: "entities.create" }]
                }
            ]
        },

        // Mengirim Pesan & Menjalankan Pencocokan Kata Kunci
        sendMessage() {
            if (!this.userInput.trim()) return;

            // Simpan pesan user
            const userText = this.userInput;
            this.messages.push({
                sender: 'user',
                text: userText,
                time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
            });
            this.userInput = '';
            this.saveHistory();

            // Jalankan pencocokan kata kunci setelah delay singkat (simulasi bot mengetik)
            setTimeout(() => {
                const response = this.findBestMatch(userText);
                this.messages.push({
                    sender: 'bot',
                    text: response.answer,
                    links: response.links || [],
                    time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
                });
                this.saveHistory();
                this.scrollToBottom();
            }, 500);

            this.scrollToBottom();
        },

        // Fungsi Inti Pencocokan Kata Kunci
        findBestMatch(text) {
            // Normalisasi input
            const normalizedInput = text.toLowerCase()
                .replace(/[.,\/#!$%\^&\*;:{}=\-_`~()?\u2014]/g, "")
                .trim();
            const inputTokens = normalizedInput.split(/\s+/);
            
            const currentFaqs = this.faqData[this.locale] || this.faqData['id'];
            let bestMatch = null;
            let highestScore = 0;

            currentFaqs.forEach(faq => {
                let score = 0;
                faq.keywords.forEach(keyword => {
                    // Cek apakah kata kunci ada di input pengguna
                    if (normalizedInput.includes(keyword)) {
                        // Kata kunci yang persis sama memberikan skor lebih tinggi
                        score += 1;
                        if (inputTokens.includes(keyword)) {
                            score += 1;
                        }
                    }
                });

                if (score > highestScore) {
                    highestScore = score;
                    bestMatch = faq;
                }
            });

            // Ambang batas (threshold) minimal skor = 1
            if (highestScore >= 1 && bestMatch) {
                return {
                    answer: bestMatch.answer,
                    links: bestMatch.links
                };
            }

            // Fallback response jika tidak ada kata kunci yang cocok
            const fallbackMsg = this.locale === 'en' 
                ? "I'm sorry, I couldn't understand your question. Here are some popular topics that might help:" 
                : "Maaf, saya belum memahami pertanyaan Anda. Berikut beberapa topik populer yang mungkin membantu:";

            return {
                answer: fallbackMsg,
                links: this.getPopularRecommendations()
            };
        },

        getPopularRecommendations() {
            const currentFaqs = this.faqData[this.locale] || this.faqData['id'];
            // Kembalikan tautan cepat dari 3 pertanyaan teratas
            return currentFaqs.slice(0, 3).map(faq => {
                return {
                    label: faq.question,
                    isTrigger: true, // Untuk memicu pertanyaan instan
                    faqId: faq.id
                };
            });
        },

        triggerQuickReply(faqId) {
            const currentFaqs = this.faqData[this.locale] || this.faqData['id'];
            const found = currentFaqs.find(f => f.id === faqId);
            if (found) {
                this.messages.push({
                    sender: 'user',
                    text: found.question,
                    time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
                });
                setTimeout(() => {
                    this.messages.push({
                        sender: 'bot',
                        text: found.answer,
                        links: found.links || [],
                        time: new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})
                    });
                    this.saveHistory();
                    this.scrollToBottom();
                }, 400);
                this.saveHistory();
                this.scrollToBottom();
            }
        },

        // Helper riwayat
        saveHistory() {
            localStorage.setItem('silaku_chat_history', JSON.stringify(this.messages));
        },
        toggleChat() {
            this.isOpen = !this.isOpen;
            localStorage.setItem('silaku_chat_open', this.isOpen);
            if (this.isOpen) {
                this.scrollToBottom();
            }
        },
        scrollToBottom() {
            this.$nextTick(() => {
                const container = this.$refs.chatContainer;
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            });
        }
    };
}
```

---

## 7. Hal-hal yang Belum Dapat Dipastikan (Uncertainties)

Dalam proses analisis kode, terdapat beberapa detail yang tidak tercantum dalam struktur data atau dokumentasi yang tersedia, sehingga dikategorikan sebagai **Belum dapat dipastikan**:

1.  **Daftar Akun Awal untuk Kaprodi, Dosen, dan Wakil Dekan:**
    *   Sistem tidak memuat akun Kaprodi, Dosen, atau Wakil Dekan dalam seeder default (`DefaultUserSeeder.php`).
    *   Akun untuk ketiga peran tersebut dibuat secara manual oleh Admin BAAK melalui formulir manajemen pengguna. Oleh karena itu, detail email/password default untuk Kaprodi dan Dosen bersifat *Belum dapat dipastikan* secara statis.
2.  **Identitas Visual & Maskot Chatbot:**
    *   Aset gambar atau avatar resmi yang mewakili chatbot belum terdefinisi dalam direktori aset publik (`public/images/`). Asset logo yang tersedia saat ini hanyalah logo fakultas dan universitas (`public/images/Logo FSIP 1.png` dan `public/images/002-UTI.png`).
3.  **Ketersediaan Chatbot di Halaman Non-Login (Landing Page):**
    *   Belum dapat dipastikan apakah chatbot harus diaktifkan secara publik di Landing Page untuk membantu pengunjung sebelum mereka login, atau hanya dibatasi untuk pengguna yang telah berhasil masuk (di dalam Dashboard).
4.  **Dukungan Chatbot dalam Bahasa Inggris secara Penuh:**
    *   Meskipun database FAQ di atas telah disiapkan untuk mendukung bilingual (ID/EN) sesuai skema Landing Page, namun halaman internal dashboard aplikasi mayoritas masih menggunakan bahasa Indonesia. Oleh karena itu, status penerapan lokalisasi chatbot secara penuh pada dashboard masih bersifat *Belum dapat dipastikan* tanpa konfirmasi kebijakan administrasi fakultas.

---

*Laporan Analisis ini disusun dengan cermat dan objektif berdasarkan kode sumber SILAKU-FSIP-UTI yang ditemukan pada workspace.*
