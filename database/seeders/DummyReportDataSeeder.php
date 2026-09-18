<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ProgramStudi;
use App\Models\DynamicEntity;
use App\Models\DynamicField;
use App\Models\DynamicRecord;
use App\Models\Alumni;
use App\Models\DataApprovalRequest;
use App\Models\ActivityLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DummyReportDataSeeder extends Seeder
{
    public function run(): void
    {
        $defaultPassword = 'f51p@UTI2026';
        $hashedPassword = Hash::make($defaultPassword);

        // 1. PASTIKAN USER PIMPINAN & BAAK UTAMA
        $baakUtama = User::updateOrCreate(
            ['email' => 'aminudin@teknokrat.ac.id'],
            [
                'name' => 'Mohammad Aminudin',
                'password' => $hashedPassword,
                'plain_password' => $defaultPassword,
                'nip' => '7700011292',
                'nip_type' => 'NITK',
                'is_active' => true,
                'can_create_users' => true,
            ]
        );
        $baakUtama->syncRoles(['BAAK']);

        $dekan = User::updateOrCreate(
            ['email' => 'hery@teknokrat.ac.id'],
            [
                'name' => 'Dr. Heri Kuswoyo, S.S., M.Hum.',
                'password' => $hashedPassword,
                'plain_password' => $defaultPassword,
                'nip' => '023080701',
                'nip_type' => 'NIK',
                'is_active' => true,
                'can_create_users' => false,
            ]
        );
        $dekan->syncRoles(['Pimpinan']);

        // 2. USER WAKIL DEKAN (WADEK)
        $wadek = User::updateOrCreate(
            ['email' => 'rido@teknokrat.ac.id'],
            [
                'name' => 'Akhyar Rido, S.S., M.A., Ph.D.',
                'password' => $hashedPassword,
                'plain_password' => $defaultPassword,
                'nip' => '023080702',
                'nip_type' => 'NIK',
                'is_active' => true,
                'can_create_users' => false,
            ]
        );
        $wadek->syncRoles(['Wakil Dekan']);

        // 3. STAFF BAAK (DENGAN AKSES TERBATAS - TIDAK BISA BUAT USER)
        $baakStaff = User::updateOrCreate(
            ['email' => 'putri.baak@teknokrat.ac.id'],
            [
                'name' => 'Putri Handayani, S.Kom.',
                'password' => $hashedPassword,
                'plain_password' => $defaultPassword,
                'nip' => '7700011405',
                'nip_type' => 'NITK',
                'is_active' => true,
                'can_create_users' => false,
            ]
        );
        $baakStaff->syncRoles(['BAAK']);

        // 4. KAPRODI-KAPRODI FSIP
        $kaprodiSS = User::updateOrCreate(
            ['email' => 'suprayogi@teknokrat.ac.id'],
            [
                'name' => 'Suprayogi, S.S., M.Hum.',
                'password' => $hashedPassword,
                'plain_password' => $defaultPassword,
                'nip' => '023080801',
                'nip_type' => 'NIDN',
                'program_studi_id' => 1, // S1 Sastra Inggris
                'is_active' => true,
                'can_create_users' => false,
            ]
        );
        $kaprodiSS->syncRoles(['Kaprodi']);

        $kaprodiPBI = User::updateOrCreate(
            ['email' => 'afrianto@teknokrat.ac.id'],
            [
                'name' => 'Dr. Afrianto, S.S., M.Hum.',
                'password' => $hashedPassword,
                'plain_password' => $defaultPassword,
                'nip' => '023080802',
                'nip_type' => 'NIDN',
                'program_studi_id' => 2, // S1 Pendidikan Bahasa Inggris
                'is_active' => true,
                'can_create_users' => false,
            ]
        );
        $kaprodiPBI->syncRoles(['Kaprodi']);

        $kaprodiPO = User::updateOrCreate(
            ['email' => 'reza.adhi@teknokrat.ac.id'],
            [
                'name' => 'Reza Adhi Nugroho, M.Pd.',
                'password' => $hashedPassword,
                'plain_password' => $defaultPassword,
                'nip' => '023080803',
                'nip_type' => 'NIDN',
                'program_studi_id' => 3, // S1 Pendidikan Olahraga
                'is_active' => true,
                'can_create_users' => false,
            ]
        );
        $kaprodiPO->syncRoles(['Kaprodi']);

        $kaprodiPMA = User::updateOrCreate(
            ['email' => 'nicky@teknokrat.ac.id'],
            [
                'name' => 'Nicky Dwi Puspaningtyas, M.Pd.',
                'password' => $hashedPassword,
                'plain_password' => $defaultPassword,
                'nip' => '023080804',
                'nip_type' => 'NIDN',
                'program_studi_id' => 4, // S1 Pendidikan Matematika
                'is_active' => true,
                'can_create_users' => false,
            ]
        );
        $kaprodiPMA->syncRoles(['Kaprodi']);

        // 5. DOSEN-DOSEN FSIP
        $dosenSS = User::updateOrCreate(
            ['email' => 'laila@teknokrat.ac.id'],
            [
                'name' => 'Laila Ulsi Qodriani, S.S., M.A.',
                'password' => $hashedPassword,
                'plain_password' => $defaultPassword,
                'nip' => '023080901',
                'nip_type' => 'NIDN',
                'program_studi_id' => 1,
                'is_active' => true,
                'can_create_users' => false,
            ]
        );
        $dosenSS->syncRoles(['Dosen']);

        $dosenPBI = User::updateOrCreate(
            ['email' => 'bertha@teknokrat.ac.id'],
            [
                'name' => 'Bertha Erika Putri, M.Pd.',
                'password' => $hashedPassword,
                'plain_password' => $defaultPassword,
                'nip' => '023080902',
                'nip_type' => 'NIDN',
                'program_studi_id' => 2,
                'is_active' => true,
                'can_create_users' => false,
            ]
        );
        $dosenPBI->syncRoles(['Dosen']);

        $dosenPMA = User::updateOrCreate(
            ['email' => 'sugama@teknokrat.ac.id'],
            [
                'name' => 'Sugama Maskar, M.PMat.',
                'password' => $hashedPassword,
                'plain_password' => $defaultPassword,
                'nip' => '023080903',
                'nip_type' => 'NIDN',
                'program_studi_id' => 4,
                'is_active' => true,
                'can_create_users' => false,
            ]
        );
        $dosenPMA->syncRoles(['Dosen']);

        $dosenPO = User::updateOrCreate(
            ['email' => 'rahmat@teknokrat.ac.id'],
            [
                'name' => 'Rahmat Kartolo, M.Pd.',
                'password' => $hashedPassword,
                'plain_password' => $defaultPassword,
                'nip' => '023080904',
                'nip_type' => 'NIDN',
                'program_studi_id' => 3,
                'is_active' => true,
                'can_create_users' => false,
            ]
        );
        $dosenPO->syncRoles(['Dosen']);

        // 6. DYNAMIC ENTITIES & FIELDS (IKU KATEGORI)
        // Entity A: Publikasi Ilmiah Dosen (root: dosen)
        $entityPublikasi = DynamicEntity::firstOrCreate(
            ['name' => 'Publikasi Ilmiah dan Jurnal Bereputasi'],
            [
                'slug' => 'publikasi-ilmiah-dan-jurnal-bereputasi',
                'description' => 'Pendataan artikel ilmiah dosen yang terindeks Sinta, Scopus, maupun WoS untuk IKU 5',
                'root_category' => 'dosen',
                'created_by' => $baakUtama->id,
                'icon' => 'academic-cap',
                'is_active' => true,
                'sort_order' => 1,
                'approval_status' => 'approved',
            ]
        );

        $fieldsPublikasi = [
            ['name' => 'Judul Artikel', 'slug' => 'judul_artikel', 'type' => 'text', 'is_required' => true, 'show_in_table' => true, 'sort_order' => 1],
            ['name' => 'Nama Jurnal', 'slug' => 'nama_jurnal', 'type' => 'text', 'is_required' => true, 'show_in_table' => true, 'sort_order' => 2],
            ['name' => 'Tingkat Akreditasi', 'slug' => 'tingkat_akreditasi', 'type' => 'select', 'options' => ['choices' => ['Sinta 1 / 2', 'Sinta 3 / 4', 'Scopus Q1 / Q2', 'Scopus Q3 / Q4', 'Prosiding Internasional']], 'is_required' => true, 'is_filterable' => true, 'is_aggregatable' => true, 'show_in_table' => true, 'sort_order' => 3],
            ['name' => 'Tahun Terbit', 'slug' => 'tahun_terbit', 'type' => 'number', 'is_required' => true, 'is_aggregatable' => true, 'show_in_table' => true, 'sort_order' => 4],
            ['name' => 'Tautan DOI / Publikasi', 'slug' => 'tautan_doi', 'type' => 'url', 'is_required' => false, 'show_in_table' => false, 'sort_order' => 5],
        ];
        foreach ($fieldsPublikasi as $f) {
            DynamicField::firstOrCreate(['entity_id' => $entityPublikasi->id, 'slug' => $f['slug']], array_merge($f, ['entity_id' => $entityPublikasi->id]));
        }

        // Entity B: Prestasi Mahasiswa (root: mahasiswa)
        $entityPrestasi = DynamicEntity::firstOrCreate(
            ['name' => 'Prestasi dan Kompetisi Mahasiswa'],
            [
                'slug' => 'prestasi-dan-kompetisi-mahasiswa',
                'description' => 'Rekam jejak perolehan prestasi lomba akademik dan non-akademik mahasiswa untuk IKU 2',
                'root_category' => 'mahasiswa',
                'created_by' => $baakUtama->id,
                'icon' => 'trophy',
                'is_active' => true,
                'sort_order' => 2,
                'approval_status' => 'approved',
            ]
        );

        $fieldsPrestasi = [
            ['name' => 'Nama Mahasiswa', 'slug' => 'nama_mahasiswa', 'type' => 'text', 'is_required' => true, 'show_in_table' => true, 'sort_order' => 1],
            ['name' => 'NPM', 'slug' => 'npm', 'type' => 'text', 'is_required' => true, 'show_in_table' => true, 'sort_order' => 2],
            ['name' => 'Nama Kejuaraan / Kompetisi', 'slug' => 'nama_kejuaraan', 'type' => 'text', 'is_required' => true, 'show_in_table' => true, 'sort_order' => 3],
            ['name' => 'Tingkat Kompetisi', 'slug' => 'tingkat_kompetisi', 'type' => 'select', 'options' => ['choices' => ['Provinsi / Wilayah', 'Nasional', 'Internasional']], 'is_required' => true, 'is_filterable' => true, 'is_aggregatable' => true, 'show_in_table' => true, 'sort_order' => 4],
            ['name' => 'Capaian Prestasi', 'slug' => 'capaian_prestasi', 'type' => 'select', 'options' => ['choices' => ['Juara 1', 'Juara 2', 'Juara 3', 'Gold Medal', 'Finalis Terpilih']], 'is_required' => true, 'is_aggregatable' => true, 'show_in_table' => true, 'sort_order' => 5],
            ['name' => 'Tahun Perolehan', 'slug' => 'tahun_perolehan', 'type' => 'number', 'is_required' => true, 'show_in_table' => true, 'sort_order' => 6],
        ];
        foreach ($fieldsPrestasi as $f) {
            DynamicField::firstOrCreate(['entity_id' => $entityPrestasi->id, 'slug' => $f['slug']], array_merge($f, ['entity_id' => $entityPrestasi->id]));
        }

        // Entity C: Kerja Sama & MoA Fakultas (root: fakultas)
        $entityMoA = DynamicEntity::firstOrCreate(
            ['name' => 'Kerja Sama dan MoA Institusi'],
            [
                'slug' => 'kerja-sama-dan-moa-institusi',
                'description' => 'Dokumentasi kerja sama dengan mitra industri, sekolah, dan perguruan tinggi untuk IKU 6',
                'root_category' => 'fakultas',
                'created_by' => $baakUtama->id,
                'icon' => 'handshake',
                'is_active' => true,
                'sort_order' => 3,
                'approval_status' => 'approved',
            ]
        );

        $fieldsMoA = [
            ['name' => 'Nama Instansi Mitra', 'slug' => 'nama_mitra', 'type' => 'text', 'is_required' => true, 'show_in_table' => true, 'sort_order' => 1],
            ['name' => 'Nomor Dokumen Kerja Sama', 'slug' => 'nomor_dokumen', 'type' => 'text', 'is_required' => true, 'show_in_table' => true, 'sort_order' => 2],
            ['name' => 'Bidang Kerja Sama', 'slug' => 'bidang_kerja_sama', 'type' => 'select', 'options' => ['choices' => ['Pendidikan & Magang MBKM', 'Riset & Publikasi Bersama', 'Pengabdian Masyarakat / Pelatihan', 'Pemberdayaan Lulusan']], 'is_required' => true, 'is_aggregatable' => true, 'show_in_table' => true, 'sort_order' => 3],
            ['name' => 'Masa Berlaku (Tahun)', 'slug' => 'masa_berlaku', 'type' => 'number', 'is_required' => true, 'show_in_table' => true, 'sort_order' => 4],
        ];
        foreach ($fieldsMoA as $f) {
            DynamicField::firstOrCreate(['entity_id' => $entityMoA->id, 'slug' => $f['slug']], array_merge($f, ['entity_id' => $entityMoA->id]));
        }

        // 7. RECORD FIKTIF DINAMIS UNTUK STATISTIK GRAFIK
        if (DynamicRecord::count() === 0) {
            // Publikasi Ilmiah Records
            DynamicRecord::create([
                'entity_id' => $entityPublikasi->id,
                'created_by' => $kaprodiPBI->id,
                'program_studi_id' => 2, // S1 PBI
                'data' => [
                    'judul_artikel' => 'Digital Literacy Integration in Higher Education English Language Teaching',
                    'nama_jurnal' => 'Indonesian Journal of Applied Linguistics (IJAL)',
                    'tingkat_akreditasi' => 'Scopus Q1 / Q2',
                    'tahun_terbit' => 2025,
                    'tautan_doi' => 'https://doi.org/10.17509/ijal.v14i2.60211',
                ],
            ]);

            DynamicRecord::create([
                'entity_id' => $entityPublikasi->id,
                'created_by' => $kaprodiSS->id,
                'program_studi_id' => 1, // S1 Sastra Inggris
                'data' => [
                    'judul_artikel' => 'Critical Discourse Analysis on Environmental Metaphors in Global News Portals',
                    'nama_jurnal' => 'Lingua Cultura Journal',
                    'tingkat_akreditasi' => 'Sinta 1 / 2',
                    'tahun_terbit' => 2025,
                    'tautan_doi' => 'https://doi.org/10.21512/lc.v19i1.10423',
                ],
            ]);

            DynamicRecord::create([
                'entity_id' => $entityPublikasi->id,
                'created_by' => $dosenPMA->id,
                'program_studi_id' => 4, // S1 Pend. Matematika
                'data' => [
                    'judul_artikel' => 'Penerapan Ethnomathematics Berbasis Budaya Lampung dalam Pembelajaran Aljabar Siswa SMA',
                    'nama_jurnal' => 'Jurnal Pendidikan Matematika dan Riset Sains',
                    'tingkat_akreditasi' => 'Sinta 1 / 2',
                    'tahun_terbit' => 2026,
                    'tautan_doi' => 'https://doi.org/10.24042/jpm.v12i1.8901',
                ],
            ]);

            DynamicRecord::create([
                'entity_id' => $entityPublikasi->id,
                'created_by' => $dosenPO->id,
                'program_studi_id' => 3, // S1 Pend. Olahraga
                'data' => [
                    'judul_artikel' => 'Analisis Kapasitas VO2 Max dan Biomotor Atlet Futsal Usia Muda Menggunakan Metode HIIT',
                    'nama_jurnal' => 'Jurnal Keolahragaan Indonesia',
                    'tingkat_akreditasi' => 'Sinta 3 / 4',
                    'tahun_terbit' => 2025,
                    'tautan_doi' => 'https://doi.org/10.21831/jki.v11i2.45102',
                ],
            ]);

            // Prestasi Mahasiswa Records
            DynamicRecord::create([
                'entity_id' => $entityPrestasi->id,
                'created_by' => $kaprodiSS->id,
                'program_studi_id' => 1,
                'data' => [
                    'nama_mahasiswa' => 'Aldi Kurniawan',
                    'npm' => '2211010045',
                    'nama_kejuaraan' => 'National University Debating Championship (NUDC) LLDIKTI II',
                    'tingkat_kompetisi' => 'Nasional',
                    'capaian_prestasi' => 'Juara 1',
                    'tahun_perolehan' => 2025,
                ],
            ]);

            DynamicRecord::create([
                'entity_id' => $entityPrestasi->id,
                'created_by' => $dosenPBI->id,
                'program_studi_id' => 2,
                'data' => [
                    'nama_mahasiswa' => 'Siti Annisa Rahmawati',
                    'npm' => '2311020019',
                    'nama_kejuaraan' => 'International Essay & Speech Festival Bangkok',
                    'tingkat_kompetisi' => 'Internasional',
                    'capaian_prestasi' => 'Gold Medal',
                    'tahun_perolehan' => 2026,
                ],
            ]);

            DynamicRecord::create([
                'entity_id' => $entityPrestasi->id,
                'created_by' => $dosenPMA->id,
                'program_studi_id' => 4,
                'data' => [
                    'nama_mahasiswa' => 'Dinda Putri Maharani',
                    'npm' => '2211040032',
                    'nama_kejuaraan' => 'Olimpiade Nasional MIPA (ON-MIPA) Perguruan Tinggi',
                    'tingkat_kompetisi' => 'Nasional',
                    'capaian_prestasi' => 'Juara 2',
                    'tahun_perolehan' => 2025,
                ],
            ]);

            DynamicRecord::create([
                'entity_id' => $entityPrestasi->id,
                'created_by' => $kaprodiPO->id,
                'program_studi_id' => 3,
                'data' => [
                    'nama_mahasiswa' => 'Muhammad Rizky Pratama',
                    'npm' => '2211030088',
                    'nama_kejuaraan' => 'Pekan Olahraga Mahasiswa Nasional (POMNAS) Cabang Pencak Silat',
                    'tingkat_kompetisi' => 'Nasional',
                    'capaian_prestasi' => 'Juara 1',
                    'tahun_perolehan' => 2025,
                ],
            ]);

            // Kerja Sama MoA Records
            DynamicRecord::create([
                'entity_id' => $entityMoA->id,
                'created_by' => $baakUtama->id,
                'program_studi_id' => null,
                'data' => [
                    'nama_mitra' => 'Universiti Malaya - Faculty of Languages & Linguistics',
                    'nomor_dokumen' => '084/MoA/FSIP-UTI/UM/2025',
                    'bidang_kerja_sama' => 'Riset & Publikasi Bersama',
                    'masa_berlaku' => 5,
                ],
            ]);

            DynamicRecord::create([
                'entity_id' => $entityMoA->id,
                'created_by' => $baakUtama->id,
                'program_studi_id' => null,
                'data' => [
                    'nama_mitra' => 'Dinas Pendidikan dan Kebudayaan Provinsi Lampung',
                    'nomor_dokumen' => '012/MoA/DISDIKBUD-LPG/FSIP/2025',
                    'bidang_kerja_sama' => 'Pendidikan & Magang MBKM',
                    'masa_berlaku' => 3,
                ],
            ]);
        }

        // 8. DATA ALUMNI DENGAN KOORDINAT LENGKAP UNTUK PETA LEAFLET
        if (Alumni::count() === 0) {
            $alumniData = [
                [
                    'nama' => 'Dimas Fajar Ramadhani, S.Hum.',
                    'nama_perusahaan' => 'PT Bank Mandiri (Persero) Tbk',
                    'posisi' => 'Officer Development Program (ODP)',
                    'lokasi' => 'Bandar Lampung, Lampung, Indonesia',
                    'lat' => -5.429742,
                    'lng' => 105.262529,
                    'program_studi_id' => 1, // S1 Sastra Inggris
                    'created_by' => $baakUtama->id,
                ],
                [
                    'nama' => 'Nurul Aulia Dewi, S.Pd.',
                    'nama_perusahaan' => 'Sekolah Lentera Harapan Lampung',
                    'posisi' => 'Lead English Educator & Cambridge Coordinator',
                    'lokasi' => 'Bandar Lampung, Lampung, Indonesia',
                    'lat' => -5.397140,
                    'lng' => 105.266789,
                    'program_studi_id' => 2, // S1 PBI
                    'created_by' => $baakUtama->id,
                ],
                [
                    'nama' => 'Bagus Satrio Wibowo, S.Pd.',
                    'nama_perusahaan' => 'SMA Negeri 1 Metro',
                    'posisi' => 'Guru Pendidikan Jasmani Ahli Pertama (PPPK)',
                    'lokasi' => 'Metro, Lampung, Indonesia',
                    'lat' => -5.113702,
                    'lng' => 105.306813,
                    'program_studi_id' => 3, // S1 PO
                    'created_by' => $baakUtama->id,
                ],
                [
                    'nama' => 'Vina Anggraini, S.Pd.',
                    'nama_perusahaan' => 'Ruangguru (PT Ruang Raya Indonesia)',
                    'posisi' => 'Curriculum Specialist & Master Teacher Matematika',
                    'lokasi' => 'Jakarta Selatan, DKI Jakarta, Indonesia',
                    'lat' => -6.229746,
                    'lng' => 106.829518,
                    'program_studi_id' => 4, // S1 PMA
                    'created_by' => $baakUtama->id,
                ],
                [
                    'nama' => 'Rian Hidayatullah, S.Hum.',
                    'nama_perusahaan' => 'PT Shopee International Indonesia',
                    'posisi' => 'Senior Content & Localization Specialist',
                    'lokasi' => 'Jakarta Barat, DKI Jakarta, Indonesia',
                    'lat' => -6.175392,
                    'lng' => 106.790653,
                    'program_studi_id' => 1, // S1 Sastra Inggris
                    'created_by' => $baakUtama->id,
                ],
                [
                    'nama' => 'Eka Prasetyo, S.Pd.',
                    'nama_perusahaan' => 'PT Astra International Tbk',
                    'posisi' => 'Human Capital Development Specialist',
                    'lokasi' => 'Surabaya, Jawa Timur, Indonesia',
                    'lat' => -7.257472,
                    'lng' => 112.752088,
                    'program_studi_id' => 3, // S1 PO
                    'created_by' => $baakUtama->id,
                ],
                [
                    'nama' => 'Farhan Maulana Akbar, S.Hum.',
                    'nama_perusahaan' => 'EduGlobal Tokyo Language Academy',
                    'posisi' => 'Bilingual Academic Coordinator & Translator',
                    'lokasi' => 'Tokyo, Japan',
                    'lat' => 35.676192,
                    'lng' => 139.650311,
                    'program_studi_id' => 1, // S1 Sastra Inggris
                    'created_by' => $baakUtama->id,
                ],
                [
                    'nama' => 'Nadya Kartika, S.Pd.',
                    'nama_perusahaan' => 'Kuala Lumpur International School',
                    'posisi' => 'Elementary Instructional Assistant',
                    'lokasi' => 'Kuala Lumpur, Malaysia',
                    'lat' => 3.139003,
                    'lng' => 101.686855,
                    'program_studi_id' => 2, // S1 PBI
                    'created_by' => $baakUtama->id,
                ],
            ];

            foreach ($alumniData as $alm) {
                Alumni::create($alm);
            }
        }

        // 9. DATA APPROVAL REQUESTS (ANTREAN PERSETUJUAN BAAK UNTUK DI-SS)
        if (DataApprovalRequest::count() === 0) {
            DataApprovalRequest::create([
                'type' => 'record',
                'action' => 'create',
                'status' => 'pending',
                'entity_id' => $entityPublikasi->id,
                'record_id' => null,
                'payload' => [
                    'judul_artikel' => 'Corpus Linguistics Approaches to English Language Pedagogy',
                    'nama_jurnal' => 'RELC Journal (SAGE Publishing)',
                    'tingkat_akreditasi' => 'Scopus Q1 / Q2',
                    'tahun_terbit' => 2026,
                    'tautan_doi' => 'https://doi.org/10.1177/0033688225114',
                ],
                'requester_id' => $kaprodiPBI->id,
                'requester_name' => 'Dr. Afrianto, S.S., M.Hum.',
                'requester_role' => 'Kaprodi',
                'created_at' => now()->subHours(3),
            ]);

            DataApprovalRequest::create([
                'type' => 'record',
                'action' => 'create',
                'status' => 'pending',
                'entity_id' => $entityPrestasi->id,
                'record_id' => null,
                'payload' => [
                    'nama_mahasiswa' => 'Ilham Wahyudi',
                    'npm' => '2211030012',
                    'nama_kejuaraan' => 'Kejuaraan Nasional Atletik Mahasiswa Indonesia',
                    'tingkat_kompetisi' => 'Nasional',
                    'capaian_prestasi' => 'Juara 2',
                    'tahun_perolehan' => 2026,
                ],
                'requester_id' => $dosenPO->id,
                'requester_name' => 'Rahmat Kartolo, M.Pd.',
                'requester_role' => 'Dosen',
                'created_at' => now()->subHours(5),
            ]);

            DataApprovalRequest::create([
                'type' => 'alumni',
                'action' => 'create',
                'status' => 'pending',
                'entity_id' => null,
                'record_id' => null,
                'payload' => [
                    'nama' => 'Syahrul Ramadhan, S.Hum.',
                    'nama_perusahaan' => 'Kompas Gramedia Media Group',
                    'posisi' => 'Junior News Editor & Sub-Editor',
                    'lokasi' => 'Palmerah, Jakarta Pusat, Indonesia',
                    'program_studi_id' => 1,
                ],
                'requester_id' => $kaprodiSS->id,
                'requester_name' => 'Suprayogi, S.S., M.Hum.',
                'requester_role' => 'Kaprodi',
                'created_at' => now()->subMinutes(45),
            ]);
        }

        // 10. ACTIVITY LOGS (LOG AKTIVITAS OTOMATIS)
        if (ActivityLog::count() === 0) {
            ActivityLog::create([
                'user_id' => $kaprodiPBI->id,
                'actor_name' => 'Dr. Afrianto, S.S., M.Hum.',
                'actor_role' => 'Kaprodi',
                'action' => 'request_create_record',
                'description' => 'mengajukan penambahan rekaman artikel ilmiah "Corpus Linguistics Approaches..." pada kategori Publikasi Ilmiah',
                'created_at' => now()->subHours(3),
            ]);

            ActivityLog::create([
                'user_id' => $dosenPO->id,
                'actor_name' => 'Rahmat Kartolo, M.Pd.',
                'actor_role' => 'Dosen',
                'action' => 'request_create_record',
                'description' => 'mengajukan penambahan data prestasi mahasiswa "Ilham Wahyudi" pada kategori Prestasi Mahasiswa',
                'created_at' => now()->subHours(5),
            ]);

            ActivityLog::create([
                'user_id' => $kaprodiSS->id,
                'actor_name' => 'Suprayogi, S.S., M.Hum.',
                'actor_role' => 'Kaprodi',
                'action' => 'request_create_alumni',
                'description' => 'mengajukan penambahan data alumni baru "Syahrul Ramadhan, S.Hum."',
                'created_at' => now()->subMinutes(45),
            ]);

            ActivityLog::create([
                'user_id' => $dosenPMA->id,
                'actor_name' => 'Sugama Maskar, M.PMat.',
                'actor_role' => 'Dosen',
                'action' => 'create_record',
                'description' => 'menambahkan rekaman publikasi ilmiah "Penerapan Ethnomathematics Berbasis Budaya Lampung..."',
                'created_at' => now()->subDays(1),
            ]);
        }
    }
}
