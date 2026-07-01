# Laporan Implementasi Chatbot FAQ Publik "SILA"
**SILAKU - Sistem Pelaporan IKU FSIP Universitas Teknokrat Indonesia**

Laporan ini mendokumentasikan implementasi asisten virtual informasi chatbot FAQ publik bernama **SILA** (SILAKU Information Assistant). Chatbot ini dirancang khusus untuk membantu pengunjung umum (tamu/guest) sebelum masuk ke sistem utama.

---

## 1. Desain & Ruang Lingkup Integrasi

SILA diimplementasikan sepenuhnya di sisi klien (client-side) tanpa ketergantungan pada API, basis data server, maupun model AI pihak ketiga. Ini memastikan kecepatan maksimal, efisiensi sumber daya, serta perlindungan data yang ketat.

### Kebijakan Hak Akses & Privasi (Security Isolation)
1. **Khusus Pengunjung Publik (`@guest`):** Chatbot hanya disematkan di landing page (`/`) menggunakan direktif Blade `@guest`. Begitu pengguna berhasil masuk (terautentikasi), chatbot sepenuhnya dihilangkan dari tampilan.
2. **Isolasi Halaman:** Chatbot tidak dimasukkan ke dalam layout global (`app.blade.php`), halaman login, register, dashboard, atau modul administratif internal mana pun.
3. **Filter Keamanan Kata Kunci (Data Protection):** Chatbot memiliki daftar `sensitiveKeywords`. Pertanyaan pengguna yang mengandung kata kunci seperti *"password default"*, *"kredensial"*, *"db_password"*, *"seeder"*, dll. langsung diblokir di tingkat frontend dan dibalas dengan penolakan standar yang aman untuk mencegah kebocoran data.

### Logika Penundaan Respons & Indikator Mengetik (Typing Delay & Lockout)
1. **State `isTyping`:** Ditambahkan status reaktif `isTyping` untuk membedakan kondisi ketika bot sedang memproses pertanyaan.
2. **Animasi & Teks Bilingual:** Indikator mengetik reaktif disesuaikan dengan bahasa aktif situs:
   - Bahasa Indonesia: `"SILA sedang mengetik..."` dengan bounce animation.
   - Bahasa Inggris: `"SILA is typing..."` dengan bounce animation.
3. **Penundaan Tepat 2000 ms:** Menggunakan penundaan waktu `setTimeout` selama 2000 ms sebelum jawaban final bot ditayangkan dan disimpan ke dalam riwayat obrolan.
4. **Lockout Kontrol Pengguna:** Selama 2000 ms bot sedang "mengetik", tombol kirim dan seluruh opsi quick reply (baik di bawah balon obrolan maupun di dalam teks link) dinonaktifkan (`disabled`) serta input via tombol Enter ditextarea dicegah untuk menghindari pengiriman pesan berulang/spam.

---

## 2. Struktur Komponen & File Proyek

Berikut adalah file yang ditambahkan dan diubah untuk implementasi chatbot ini:

1. **`resources/views/components/chatbot/public-faq-data.blade.php` [BARU]**
   - Berisi dataset FAQ bilingual (ID/EN) yang mendalam untuk total 18 intent (termasuk penjelasan SILAKU, hak akses tiap role, prosedur lupa password, kontak admin, dan panduan login).
   - Dilengkapi keyword pencocokan, phrase eksak, keyword negatif (anti-matching), saran tindak lanjut (quick replies), serta tautan dinamis yang aman.

2. **`resources/views/components/chatbot/public-faq-chatbot.blade.php` [BARU]**
   - File template UI sekaligus penampung logika Alpine.js untuk performa mandiri yang cepat.
   - Menggunakan Tailwind CSS untuk gaya *glassmorphism* modern dengan transisi halus, dukungan mode gelap (*dark mode*), tombol bersihkan chat (trash), tombol tutup (close), status mengetik (*typing indicator*), dan input teks multiline.

3. **`resources/views/landing.blade.php` [MODIFIKASI]**
   - Menambahkan pemanggilan komponen `<x-chatbot.public-faq-chatbot />` yang dibungkus oleh direktif `@guest` di bagian sebelum penutup body (`</body>`).

4. **`resources/js/public-faq-chatbot.js` [BARU]**
   - Menyimpan salinan source code logika pencocokan kata kunci berbobot dan sanitasi input sebagai referensi pengembangan terpisah.

---

## 3. Hasil Pengujian Fungsional (Browser Verification)

Pengujian fungsionalitas dilakukan menggunakan agen penjelajah otomatis pada lingkungan lokal (`http://127.0.0.1:8000`) dengan hasil sebagai berikut:

| No | Kasus Uji | Ekspektasi | Hasil | Status |
|----|-----------|------------|-------|--------|
| 1 | Visibilitas Tombol Floating | Tombol SILA melayang dengan animasi pulse tampil di sudut kanan bawah landing page publik. | Tombol tampil dengan ikon balon chat yang kontras dan elegan. | **Lulus** |
| 2 | Pembukaan Jendela Chat | Mengklik tombol melayang membuka jendela interaktif SILA. | Jendela chat terbuka dengan pesan selamat datang bilingual dan 5 tombol quick reply. | **Lulus** |
| 3 | Indikator Mengetik & Delay 2 Detik | Mengirim pesan atau mengklik quick reply langsung memicu indikator mengetik dan menunda respons selama 2 detik. | Teks mengetik ("SILA sedang mengetik...") tampil tepat selama 2000 ms sebelum digantikan respons asli. | **Lulus** |
| 4 | Lockout Kontrol saat Mengetik | Selama indikator mengetik aktif, pengguna tidak dapat mengirim pesan baru atau mengklik saran lain. | Tombol kirim dan quick reply dinonaktifkan (`disabled`); menekan Enter pada input tidak memicu pengiriman baru. | **Lulus** |
| 5 | Pencocokan Tanya-Jawab | Mengklik saran "Apa itu SILAKU?" memberikan penjelasan tentang IKU setelah 2 detik. | Bot merespons dengan deskripsi IKU FSIP secara tepat setelah penundaan mengetik selesai. | **Lulus** |
| 6 | Pencarian Bebas & Tautan | Mengetik "bagaimana cara login" memicu respons panduan login dengan tombol aksi link setelah 2 detik. | Bot memberikan langkah-langkah login dan menampilkan tombol "Buka Halaman Login" ke `/login`. | **Lulus** |
| 7 | Intersepsi Keamanan | Mengetik pencarian sensitif seperti "password default admin". | Bot memotong proses pencarian, menunggu 2 detik dalam status mengetik, lalu menampilkan peringatan keamanan. | **Lulus** |
| 8 | Integrasi Lokalisasi (Bilingual) | Mengubah bahasa landing page ke EN / ID mengubah bahasa chatbot dan teks mengetik secara otomatis. | Logika `app()->getLocale()` Laravel mendeteksi locale. Indikator mengetik berubah menjadi "SILA is typing..." saat dalam bahasa Inggris. | **Lulus** |
| 9 | Hapus Riwayat Chat | Mengklik tombol bersihkan chat (sampah) mengosongkan percakapan dan mereset status mengetik. | Riwayat pesan terhapus di localStorage, status isTyping kembali false, dan chat kembali ke sapaan awal. | **Lulus** |
| 10| Penutupan Chatbot | Mengklik tombol close ("X") menutup jendela chat dan mereset status mengetik. | Jendela tertutup, status isTyping kembali false, dan tombol melayang kembali tampil. | **Lulus** |
| 11| Pembatasan Halaman (Auth Boundary) | Mengakses halaman `/login` atau setelah login. | Chatbot tidak dimuat sama sekali di halaman login dan dashboard internal. | **Lulus** |
| 12| Bersih dari Error Console | Console log bersih dari kesalahan JavaScript. | Tidak ada error JavaScript (termasuk Alpine.js) yang tercatat di console. | **Lulus** |

---

## 4. Keunggulan Teknis & Keamanan

- **Zero Server Overhead:** Seluruh kalkulasi teks, sanitasi kata kunci, skoring kata kunci, dan transisi bahasa diproses sepenuhnya di browser client, menghemat resource server.
- **Secure State Persistence:** Menggunakan `localStorage` untuk mempertahankan riwayat pesan percakapan dan status buka/tutup chatbot antar muatan halaman tanpa menyimpan status indikator mengetik sementara.
- **Responsive & Premium UI:** Tata letak disesuaikan untuk perangkat mobile maupun desktop dengan transisi mikro-interaksi yang memanjakan mata.
- **Bebas Kredensial:** Dataset FAQ dibersihkan secara penuh dari kredensial bawaan maupun email/password admin riil untuk menjaga keutuhan keamanan sistem.
