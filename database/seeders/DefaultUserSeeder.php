<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class DefaultUserSeeder extends Seeder
{
    public function run(): void
    {
        // BAAK Super Admin
        $baak = User::create([
            'name' => 'Mohammad Aminudin',
            'email' => 'aminudin@teknokrat.ac.id',
            'password' => 'amin123F51p@UTI',
            'plain_password' => 'amin123F51p@UTI',
            'nip' => '7700011292',
            'nip_type' => 'NITK',
            'is_active' => true,
        ]);
        $baak->assignRole('BAAK');

        // Pimpinan (Dekan)
        $pimpinan = User::create([
            'name' => 'Dr. Heri Kuswoyo, S.S., M.Hum.',
            'email' => 'hery@teknokrat.ac.id',
            'password' => 'heri123F51p@UTI',
            'plain_password' => 'heri123F51p@UTI',
            'nip' => '023080701',
            'nip_type' => 'NIK',
            'is_active' => true,
        ]);
        $pimpinan->assignRole('Pimpinan');

        // Wakil Dekan
        $wadek = User::create([
            'name' => 'Wakil Dekan FSIP',
            'email' => 'wadek@teknokrat.ac.id',
            'password' => 'wadek123F51p@UTI',
            'plain_password' => 'wadek123F51p@UTI',
            'nip' => '023080702',
            'nip_type' => 'NIK',
            'is_active' => true,
        ]);
        $wadek->assignRole('Wakil Dekan');

        // Get Program Studi S1 Pendidikan Bahasa Inggris (PBI)
        $pbi = ProgramStudi::where('code', 'S1PBI')->first();
        $pbiId = $pbi ? $pbi->id : null;

        // Kaprodi
        $kaprodi = User::create([
            'name' => 'Kaprodi Pendidikan Bahasa Inggris',
            'email' => 'kaprodi.pbi@teknokrat.ac.id',
            'password' => 'password',
            'plain_password' => 'password',
            'nip' => '199001012020121003',
            'nip_type' => 'NIP',
            'program_studi_id' => $pbiId,
            'is_active' => true,
        ]);
        $kaprodi->assignRole('Kaprodi');

        // Dosen
        $dosen = User::create([
            'name' => 'Dr. Ahmad Fauzi',
            'email' => 'dosen.pbi@teknokrat.ac.id',
            'password' => 'password',
            'plain_password' => 'password',
            'nip' => '199201012021121004',
            'program_studi_id' => $pbiId,
            'is_active' => true,
        ]);
        $dosen->assignRole('Dosen');

        // Seed mock activity logs for notification center
        \App\Models\ActivityLog::create([
            'user_id' => $kaprodi->id,
            'actor_name' => 'Kaprodi Pendidikan Bahasa Inggris',
            'actor_role' => 'Kaprodi',
            'action' => 'request_create_category',
            'description' => 'mengajukan pembuatan kategori data "Penelitian Dosen"',
            'created_at' => now()->subMinutes(12),
        ]);

        \App\Models\ActivityLog::create([
            'user_id' => $dosen->id,
            'actor_name' => 'Dr. Ahmad Fauzi',
            'actor_role' => 'Dosen',
            'action' => 'request_create_alumni',
            'description' => 'mengajukan penambahan data alumni "Fahmi Pratama"',
            'created_at' => now()->subHours(2),
        ]);
    }
}
