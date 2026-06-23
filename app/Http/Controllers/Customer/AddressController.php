<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $alamat = Alamat::where('user_id', auth()->id())
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('customer.addresses.index', compact('alamat'));
    }

    public function create()
    {
        return view('customer.addresses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'label' => 'required|string|max:50',
            'nama_penerima' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'provinsi' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'city_id' => 'required|string|max:20',
            'kecamatan' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'alamat_lengkap' => 'required|string',
            'is_default' => 'boolean',
        ]);

        if ($request->is_default) {
            Alamat::where('user_id', auth()->id())->update(['is_default' => false]);
        }

        Alamat::create([
            'user_id' => auth()->id(),
            'label' => $request->label,
            'nama_penerima' => $request->nama_penerima,
            'no_hp' => $request->no_hp,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'city_id' => $request->city_id,
            'kecamatan' => $request->kecamatan,
            'kode_pos' => $request->kode_pos,
            'alamat_lengkap' => $request->alamat_lengkap,
            'is_default' => $request->boolean('is_default'),
        ]);

        return redirect()->route('customer.addresses.index')->with('success', 'Alamat berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $alamat = Alamat::where('user_id', auth()->id())->findOrFail($id);
        return view('customer.addresses.edit', compact('alamat'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'label' => 'required|string|max:50',
            'nama_penerima' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'provinsi' => 'required|string|max:100',
            'kota' => 'required|string|max:100',
            'city_id' => 'required|string|max:20',
            'kecamatan' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'alamat_lengkap' => 'required|string',
            'is_default' => 'boolean',
        ]);

        $alamat = Alamat::where('user_id', auth()->id())->findOrFail($id);

        if ($request->is_default) {
            Alamat::where('user_id', auth()->id())
                ->where('id', '!=', $id)
                ->update(['is_default' => false]);
        }

        $alamat->update([
            'label' => $request->label,
            'nama_penerima' => $request->nama_penerima,
            'no_hp' => $request->no_hp,
            'provinsi' => $request->provinsi,
            'kota' => $request->kota,
            'city_id' => $request->city_id,
            'kecamatan' => $request->kecamatan,
            'kode_pos' => $request->kode_pos,
            'alamat_lengkap' => $request->alamat_lengkap,
            'is_default' => $request->boolean('is_default'),
        ]);

        return redirect()->route('customer.addresses.index')->with('success', 'Alamat berhasil diupdate.');
    }

    public function destroy($id)
    {
        $alamat = Alamat::where('user_id', auth()->id())->findOrFail($id);
        $alamat->delete();

        return redirect()->route('customer.addresses.index')->with('success', 'Alamat berhasil dihapus.');
    }
}
