<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use App\Models\Kupon;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    /** RajaOngkir V2 Base URL */
    private const RAJAONGKIR_BASE_URL = 'https://rajaongkir.komerce.id/api/v1';

    /** HTTP timeout in seconds */
    private const REQUEST_TIMEOUT = 30;

    private function rajaOngkirApiKey(): string
    {
        return Setting::get('rajaongkir_api_key', env('RAJAONGKIR_API_KEY', ''));
    }

    private function rajaOngkirOriginCityId(): string
    {
        return Setting::get('store_city_id', env('RAJAONGKIR_ORIGIN_CITY_ID', '501'));
    }

    private function rajaOngkirHeaders(): array
    {
        return [
            'key' => $this->rajaOngkirApiKey(),
        ];
    }

    /**
     * Make a GET request to RajaOngkir V2 API with timeout and error handling.
     */
    private function rajaOngkirGet(string $endpoint, array $query = []): ?array
    {
        try {
            $response = Http::withHeaders($this->rajaOngkirHeaders())
                ->timeout(self::REQUEST_TIMEOUT)
                ->get(self::RAJAONGKIR_BASE_URL . $endpoint, $query);

            if ($response->successful()) {
                $json = $response->json();
                return $json['data'] ?? [];
            }

            \Log::warning('RajaOngkir API error', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            \Log::error('RajaOngkir API request failed', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Make a POST request to RajaOngkir V2 API (form-urlencoded) with timeout.
     */
    private function rajaOngkirPost(string $endpoint, array $data): ?array
    {
        try {
            $response = Http::withHeaders($this->rajaOngkirHeaders())
                ->timeout(self::REQUEST_TIMEOUT)
                ->asForm()
                ->post(self::RAJAONGKIR_BASE_URL . $endpoint, $data);

            if ($response->successful()) {
                $json = $response->json();
                return $json['data'] ?? [];
            }

            \Log::warning('RajaOngkir API POST error', [
                'endpoint' => $endpoint,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            \Log::error('RajaOngkir API POST request failed', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }

    public function getProvinces()
    {
        $provinces = Cache::remember('rajaongkir_v2_provinces', 86400, function () {
            $data = $this->rajaOngkirGet('/destination/province');
            return $data ?? [];
        });

        return response()->json($provinces);
    }

    public function getCities($provinceId)
    {
        $cities = Cache::remember("rajaongkir_v2_cities_{$provinceId}", 86400, function () use ($provinceId) {
            $data = $this->rajaOngkirGet("/destination/city/{$provinceId}");
            return $data ?? [];
        });

        return response()->json($cities);
    }

    public function shippingCost(Request $request)
    {
        $request->validate([
            'address_id' => 'required|integer|exists:alamats,id',
            'weight' => 'required|integer|min:1',
            'courier' => 'required|string',
        ]);

        // Resolve city_id from the user's address
        $alamat = Alamat::where('id', $request->address_id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$alamat) {
            return response()->json(['message' => 'Alamat tidak ditemukan.'], 404);
        }

        if (!$alamat->city_id) {
            return response()->json(['message' => 'Alamat belum memiliki city_id. Silakan perbarui alamat Anda.'], 400);
        }

        $destination = (int) $alamat->city_id;
        $origin = (int) $this->rajaOngkirOriginCityId();
        $courier = $request->courier;

        $cacheKey = "shipping_v2_{$origin}_{$destination}_{$request->weight}_{$courier}";

        $services = Cache::remember($cacheKey, 1800, function () use ($origin, $destination, $courier, $request) {
            $data = $this->rajaOngkirPost('/calculate/domestic-cost', [
                'origin' => $origin,
                'destination' => $destination,
                'weight' => $request->weight,
                'courier' => $courier,
            ]);

            if ($data === null) {
                return null;
            }

            // V2 response: flat array of { name, code, service, description, cost, etd }
            $services = [];
            foreach ($data as $item) {
                $services[] = [
                    'service' => $item['service'] ?? '',
                    'description' => $item['description'] ?? '',
                    'cost' => $item['cost'] ?? 0,
                    'etd' => $item['etd'] ?? '-',
                    'courier' => $item['code'] ?? $courier,
                    'courier_name' => $item['name'] ?? strtoupper($courier),
                ];
            }

            return $services;
        });

        if ($services === null) {
            return response()->json([
                'message' => 'Gagal menghitung ongkos kirim. Pastikan koneksi internet Anda stabil dan coba lagi.',
            ], 500);
        }

        return response()->json($services);
    }

    public function validateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'subtotal' => 'required|integer|min:0',
        ]);

        $kupon = Kupon::where('kode', $request->code)->first();

        if (!$kupon) {
            return response()->json(['valid' => false, 'message' => 'Kupon tidak ditemukan.']);
        }

        if (!$kupon->isValid()) {
            return response()->json(['valid' => false, 'message' => 'Kupon sudah tidak berlaku.']);
        }

        if ($kupon->min_order > 0 && $request->subtotal < $kupon->min_order) {
            return response()->json([
                'valid' => false,
                'message' => 'Minimal order Rp ' . number_format($kupon->min_order, 0, ',', '.'),
            ]);
        }

        $discount = $kupon->calculateDiscount($request->subtotal);

        return response()->json([
            'valid' => true,
            'discount' => $discount,
            'discount_formatted' => 'Rp ' . number_format($discount, 0, ',', '.'),
            'message' => 'Kupon berhasil diterapkan.',
        ]);
    }
}
