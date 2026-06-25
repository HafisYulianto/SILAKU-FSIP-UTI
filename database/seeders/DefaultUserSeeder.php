<?php

namespace Database\Seeders;

use App\Models\User;
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

        // Seed mock activity logs for notification center (using null user_id so no extra accounts needed)
        \App\Models\ActivityLog::create([
            'user_id' => null,
            'actor_name' => 'Kaprodi Sastra Inggris',
            'actor_role' => 'Kaprodi',
            'action' => 'request_create_category',
            'description' => 'mengajukan pembuatan kategori data "Penelitian Dosen Hibah Nasional"',
            'created_at' => now()->subMinutes(12),
        ]);

        \App\Models\ActivityLog::create([
            'user_id' => null,
            'actor_name' => 'Dr. Ahmad Fauzi',
            'actor_role' => 'Dosen',
            'action' => 'request_create_alumni',
            'description' => 'mengajukan penambahan data alumni "Fahmi Pratama"',
            'created_at' => now()->subHours(2),
        ]);
    }
}
