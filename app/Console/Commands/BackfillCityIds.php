<?php

namespace App\Console\Commands;

use App\Models\Alamat;
use App\Models\Setting;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class BackfillCityIds extends Command
{
    protected $signature = 'backfill:city-ids';
    protected $description = 'Backfill city_id for existing addresses using RajaOngkir V2 API';

    private const BASE_URL = 'https://rajaongkir.komerce.id/api/v1';
    private const TIMEOUT = 30;

    public function handle()
    {
        $apiKey = Setting::get('rajaongkir_api_key', env('RAJAONGKIR_API_KEY', ''));
        if (empty($apiKey)) {
            $this->error('RajaOngkir API key is not configured.');
            return Command::FAILURE;
        }

        $headers = ['key' => $apiKey];
        $addresses = Alamat::whereNull('city_id')->orWhere('city_id', '')->get();

        if ($addresses->isEmpty()) {
            $this->info('All addresses already have city_id. Nothing to do.');
            return Command::SUCCESS;
        }

        $this->info("Found {$addresses->count()} address(es) without city_id.");
        $this->info('Fetching provinces from RajaOngkir V2...');

        // Fetch provinces
        $provincesResp = Http::withHeaders($headers)
            ->timeout(self::TIMEOUT)
            ->get(self::BASE_URL . '/destination/province');

        if (!$provincesResp->successful()) {
            $this->error('Failed to fetch provinces. Status: ' . $provincesResp->status());
            $this->error('Response: ' . $provincesResp->body());
            return Command::FAILURE;
        }

        $provinces = $provincesResp->json()['data'] ?? [];
        $this->info('Found ' . count($provinces) . ' provinces.');

        // Fetch all cities
        $allCities = [];
        $bar = $this->output->createProgressBar(count($provinces));
        $bar->start();

        foreach ($provinces as $province) {
            $citiesResp = Http::withHeaders($headers)
                ->timeout(self::TIMEOUT)
                ->get(self::BASE_URL . '/destination/city/' . $province['id']);

            if ($citiesResp->successful()) {
                $cities = $citiesResp->json()['data'] ?? [];
                foreach ($cities as $city) {
                    $city['_province'] = $province['name'];
                    $allCities[] = $city;
                }
            }
            $bar->advance();
        }
        $bar->finish();
        $this->newLine(2);
        $this->info("Fetched " . count($allCities) . " cities from RajaOngkir V2.");

        // Match and update
        $updated = 0;
        $skipped = 0;

        foreach ($addresses as $address) {
            $kota = trim(strtolower($address->kota));
            $provinsi = trim(strtolower($address->provinsi));

            if (empty($kota)) {
                $skipped++;
                $this->warn("  SKIP: Address #{$address->id} has empty kota.");
                continue;
            }

            // Try exact match first, then fuzzy
            $match = null;

            // Exact match (name + province)
            foreach ($allCities as $city) {
                $cityName = trim(strtolower($city['name']));
                $cityProv = trim(strtolower($city['_province'] ?? ''));

                if ($kota === $cityName && $provinsi === $cityProv) {
                    $match = $city;
                    break;
                }
            }

            // Fallback: name only
            if (!$match) {
                foreach ($allCities as $city) {
                    $cityName = trim(strtolower($city['name']));
                    if ($kota === $cityName || str_contains($cityName, $kota) || str_contains($kota, $cityName)) {
                        $match = $city;
                        break;
                    }
                }
            }

            if ($match) {
                $address->update(['city_id' => (string) $match['id']]);
                $updated++;
                $this->line("  OK: Address #{$address->id} \"{$address->kota}\" → city_id={$match['id']} ({$match['name']})");
            } else {
                $skipped++;
                $this->warn("  SKIP: Address #{$address->id} \"{$address->kota}\" — no match in RajaOngkir V2.");
            }
        }

        $this->newLine();
        $this->info("Done! Updated: {$updated}, Skipped: {$skipped}");

        return Command::SUCCESS;
    }
}
