<?php

namespace App\Console\Commands;

use App\Models\Alumni;
use App\Services\GeocodingService;
use Illuminate\Console\Command;

class GeocodeAlumni extends Command
{
    protected $signature   = 'alumni:geocode {--force : Re-geocode all, even if coordinates exist}';
    protected $description = 'Geocode lokasi alumni ke koordinat lat/lng via Nominatim';

    public function handle(GeocodingService $geocodingService): int
    {
        $query = Alumni::whereNotNull('lokasi');

        if (!$this->option('force')) {
            $query->whereNull('lat');
        }

        $alumni = $query->get();

        if ($alumni->isEmpty()) {
            $this->info('Tidak ada alumni yang perlu di-geocode.');
            return 0;
        }

        $this->info("Geocoding {$alumni->count()} alumni...");
        $bar = $this->output->createProgressBar($alumni->count());
        $bar->start();

        $success = 0;
        $fail    = 0;

        foreach ($alumni as $a) {
            // Nominatim rate limit: 1 request/second
            sleep(1);

            $result = $geocodingService->geocodeAlumni($a);
            if ($result) {
                $success++;
            } else {
                $fail++;
                $this->newLine();
                $this->warn("  Gagal geocode: \"{$a->lokasi}\" (Alumni: {$a->nama})");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info("Selesai: {$success} berhasil, {$fail} gagal.");

        return 0;
    }
}
