<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $fields = [
            'store_name',
            'tagline',
            'contact_email',
            'contact_phone',
            'midtrans_server_key',
            'midtrans_client_key',
            'midtrans_is_production',
            'rajaongkir_api_key',
            'store_city_id',
        ];

        foreach ($fields as $field) {
            $value = $request->input($field, '');
            if ($field === 'midtrans_is_production') {
                $value = $request->has('midtrans_is_production') ? '1' : '0';
            }
            Setting::set($field, $value);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }
}
