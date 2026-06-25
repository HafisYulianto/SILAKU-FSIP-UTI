<?php

namespace Database\Seeders;

use App\Models\Alumni;
use App\Models\ProgramStudi;
use App\Models\User;
use Illuminate\Database\Seeder;

class AlumniSeeder extends Seeder
{
    public function run(): void
    {
        $creator = User::first();
        if (!$creator) return;

        $prodiSastraInggris = ProgramStudi::where('code', 'S1SS')->first();
        $prodiPendidikanInggris = ProgramStudi::where('code', 'S1PBI')->first();
        $prodiPendidikanOlahraga = ProgramStudi::where('code', 'S1PO')->first();
        $prodiPendidikanMatematika = ProgramStudi::where('code', 'S1PMA')->first();

        $alumnis = [
            [
                'nama' => 'Ahmad Fauzi',
                'nama_perusahaan' => 'PT. GoTo Gojek Tokopedia',
                'posisi' => 'Software Engineer',
                'lokasi' => 'Bandar Lampung',
                'program_studi_id' => $prodiPendidikanMatematika?->id,
                'created_by' => $creator->id,
            ],
            [
                'nama' => 'Rina Amalia',
                'nama_perusahaan' => 'Bukalapak',
                'posisi' => 'Product Manager',
                'lokasi' => 'Metro',
                'program_studi_id' => $prodiSastraInggris?->id,
                'created_by' => $creator->id,
            ],
            [
                'nama' => 'Budi Santoso',
                'nama_perusahaan' => 'Dinas Pendidikan Lampung Selatan',
                'posisi' => 'Staff Administrasi',
                'lokasi' => 'Lampung Selatan',
                'program_studi_id' => $prodiPendidikanInggris?->id,
                'created_by' => $creator->id,
            ],
            [
                'nama' => 'Dewi Lestari',
                'nama_perusahaan' => 'BCA Syariah Bandar Lampung',
                'posisi' => 'Customer Relations Officer',
                'lokasi' => 'Bandar Lampung',
                'program_studi_id' => $prodiSastraInggris?->id,
                'created_by' => $creator->id,
            ],
            [
                'nama' => 'Hendra Wijaya',
                'nama_perusahaan' => 'SMA Negeri 1 Pringsewu',
                'posisi' => 'Guru Pendidikan Olahraga',
                'lokasi' => 'Pringsewu',
                'program_studi_id' => $prodiPendidikanOlahraga?->id,
                'created_by' => $creator->id,
            ],
            [
                'nama' => 'Siti Aminah',
                'nama_perusahaan' => 'PT. Telkom Indonesia',
                'posisi' => 'Data Analyst',
                'lokasi' => 'Bandar Lampung',
                'program_studi_id' => $prodiPendidikanMatematika?->id,
                'created_by' => $creator->id,
            ],
            [
                'nama' => 'Dwi Cahyono',
                'nama_perusahaan' => 'Bank Lampung Cabang Metro',
                'posisi' => 'Teller',
                'lokasi' => 'Metro',
                'program_studi_id' => $prodiPendidikanMatematika?->id,
                'created_by' => $creator->id,
            ],
            [
                'nama' => 'Megawati',
                'nama_perusahaan' => 'Ruangguru',
                'posisi' => 'Instructional Designer',
                'lokasi' => 'Lampung Tengah',
                'program_studi_id' => $prodiPendidikanInggris?->id,
                'created_by' => $creator->id,
            ],
        ];

        foreach ($alumnis as $alumniData) {
            Alumni::create($alumniData);
        }
    }
}
