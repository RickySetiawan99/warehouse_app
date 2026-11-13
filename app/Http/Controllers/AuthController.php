<?php

namespace App\Http\Controllers;

use App\Services\WarehouseApiService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function __construct(protected WarehouseApiService $api) {}

    public function showLogin()
    {
        if (session()->has('api_token')) {
            return redirect()->route('dashboard');
        }

        $store = $this->api->getStoreInfo();

        return view('auth.login', compact('store'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $res = $this->api->login($credentials['email'], $credentials['password']);

        if (! $res['ok']) {
            return back()
                ->withErrors(['email' => $res['message'] ?? 'Login gagal.'])
                ->withInput($request->only('email'));
        }

        $warehouses = $res['warehouses'] ?? [];

        session([
            'api_token' => $res['token'],
            'user' => $res['user'] ?? [],
            'tenant' => $res['tenant'] ?? [],
            'warehouses' => $warehouses,
            'active_warehouse_id' => $warehouses[0]['id'] ?? null,
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Selamat datang di operasional gudang.');
    }

    public function selectWarehouse(Request $request)
    {
        $validated = $request->validate([
            'warehouse_id' => ['required', 'integer'],
        ]);

        $warehouses = session('warehouses', []);
        $warehouseId = (int) $validated['warehouse_id'];

        if (! empty($warehouses)) {
            $exists = collect($warehouses)->contains(fn ($wh) => (int) ($wh['id'] ?? 0) === $warehouseId);
            if (! $exists) {
                return back()->withErrors(['warehouse_id' => 'Gudang yang dipilih tidak terdaftar.']);
            }
        }

        session(['active_warehouse_id' => $warehouseId]);

        return back()->with('success', 'Lokasi gudang aktif diperbarui.');
    }

    public function logout()
    {
        $this->api->logout();

        return redirect()->route('login')
            ->with('success', 'Berhasil keluar dari sesi gudang.');
    }
}
