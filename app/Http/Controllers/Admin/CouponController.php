<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $kupons = Kupon::latest()->paginate(15);
        return view('admin.coupons.index', compact('kupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:kupons,kode',
            'tipe' => 'required|in:fixed,percent',
            'nilai' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
            'is_active' => 'boolean',
        ]);

        $data = $request->except('is_active');
        $data['is_active'] = $request->has('is_active');
        $data['used_count'] = 0;

        Kupon::create($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Kupon berhasil ditambahkan.');
    }

    public function edit(Kupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Kupon $coupon)
    {
        $request->validate([
            'kode' => 'required|string|max:50|unique:kupons,kode,' . $coupon->id,
            'tipe' => 'required|in:fixed,percent',
            'nilai' => 'required|numeric|min:0',
            'min_order' => 'nullable|numeric|min:0',
            'max_uses' => 'nullable|integer|min:0',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date|after_or_equal:valid_from',
        ]);

        $data = $request->except('is_active');
        $data['is_active'] = $request->has('is_active');

        $coupon->update($data);

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Kupon berhasil diperbarui.');
    }

    public function destroy(Kupon $coupon)
    {
        $coupon->delete();

        return redirect()->route('admin.coupons.index')
            ->with('success', 'Kupon berhasil dihapus.');
    }
}
