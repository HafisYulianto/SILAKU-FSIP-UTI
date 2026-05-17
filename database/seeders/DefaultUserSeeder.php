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
            'name' => 'Admin BAAK',
            'email' => 'baak@teknokrat.ac.id',
            'password' => 'password',
            'plain_password' => 'password',
            'nip' => '198001012000121001',
            'is_active' => true,
        ]);
        $baak->assignRole('BAAK');

        // Pimpinan
        $pimpinan = User::create([
            'name' => 'Dekan FSIP',
            'email' => 'pimpinan@teknokrat.ac.id',
            'password' => 'password',
            'plain_password' => 'password',
            'nip' => '197501012000121002',
            'is_active' => true,
        ]);
        $pimpinan->assignRole('Pimpinan');

    }
}
