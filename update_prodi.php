<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$programStudi = [
    [
        'old_codes' => ['SI', 'S1SS'],
        'name' => 'S1 Sastra Inggris',
        'code' => 'S1SS',
        'description' => 'Program Studi S1 Sastra Inggris',
    ],
    [
        'old_codes' => ['PBI', 'S1PBI'],
        'name' => 'S1 Pendidikan Bahasa Inggris',
        'code' => 'S1PBI',
        'description' => 'Program Studi S1 Pendidikan Bahasa Inggris',
    ],
    [
        'old_codes' => ['POR', 'S1PO'],
        'name' => 'S1 Pendidikan Olahraga',
        'code' => 'S1PO',
        'description' => 'Program Studi S1 Pendidikan Olahraga',
    ],
    [
        'old_codes' => ['PM', 'S1PMA'],
        'name' => 'S1 Pendidikan Matematika',
        'code' => 'S1PMA',
        'description' => 'Program Studi S1 Pendidikan Matematika',
    ],
    [
        'old_codes' => ['S2PBI', 'S2BI'],
        'name' => 'S2 Magister Bahasa Inggris',
        'code' => 'S2BI',
        'description' => 'Program Studi S2 Magister Bahasa Inggris',
    ],
];

foreach ($programStudi as $prodiData) {
    $prodi = App\Models\ProgramStudi::whereIn('code', $prodiData['old_codes'])->first();
    if ($prodi) {
        $prodi->update([
            'name' => $prodiData['name'],
            'code' => $prodiData['code'],
            'description' => $prodiData['description'],
        ]);
    } else {
        App\Models\ProgramStudi::create([
            'name' => $prodiData['name'],
            'code' => $prodiData['code'],
            'description' => $prodiData['description'],
        ]);
    }
}

echo 'Done.';
