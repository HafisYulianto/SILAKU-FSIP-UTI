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
            'password' => 'amin123',
            'plain_password' => 'amin123',
            'nip' => '7700011292',
            'is_active' => true,
        ]);
        $baak->assignRole('BAAK');

        // Pimpinan
        $pimpinan = User::create([
            'name' => 'Dr. Heri Kuswoyo, S.S., M.Hum.',
            'email' => 'hery@teknokrat.ac.id',
            'password' => 'heri123',
            'plain_password' => 'heri123',
            'nip' => '023080701',
            'is_active' => true,
        ]);
        $pimpinan->assignRole('Pimpinan');

    }
}
