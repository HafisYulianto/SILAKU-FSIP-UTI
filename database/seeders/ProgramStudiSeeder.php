<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    public function run(): void
    {
        $programStudi = [
            [
                'name' => 'S1 Sastra Inggris',
                'code' => 'SI',
                'description' => 'Program Studi S1 Sastra Inggris',
            ],
            [
                'name' => 'S1 Pendidikan Bahasa Inggris',
                'code' => 'PBI',
                'description' => 'Program Studi S1 Pendidikan Bahasa Inggris',
            ],
            [
                'name' => 'S1 Pendidikan Olahraga',
                'code' => 'POR',
                'description' => 'Program Studi S1 Pendidikan Olahraga',
            ],
            [
                'name' => 'S1 Pendidikan Matematika',
                'code' => 'PM',
                'description' => 'Program Studi S1 Pendidikan Matematika',
            ],
            [
                'name' => 'S2 Pendidikan Bahasa Inggris',
                'code' => 'S2PBI',
                'description' => 'Program Studi S2 Pendidikan Bahasa Inggris',
            ],
        ];

        foreach ($programStudi as $prodi) {
            ProgramStudi::updateOrCreate(
                ['code' => $prodi['code']],
                $prodi
            );
        }
    }
}
