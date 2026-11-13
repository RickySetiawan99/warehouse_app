@extends('layouts.app')

@section('title', 'Masuk Staf Gudang')

@section('content')
@php
  $tenant = $store ?? session('tenant', []);
  $storeName = $tenant['clean_name'] ?? $tenant['name'] ?? 'Grosera';
@endphp

<div class="min-h-screen flex items-center justify-center p-4 py-10">
  <div class="w-full max-w-4xl flat-surface rounded-2xl overflow-hidden grid grid-cols-1 md:grid-cols-12">
    <!-- Left Industrial Showcase Panel -->
    <div class="md:col-span-5 p-7 sm:p-9 flex flex-col justify-between border-b md:border-b-0 md:border-r" style="background-color: var(--wms-surface-alt); border-color: var(--wms-divider);">
      <div class="space-y-6">
        <div class="flex items-center gap-3">
          <div class="w-11 h-11 rounded-xl flex items-center justify-center font-extrabold text-lg" style="background-color: var(--wms-accent); color: var(--wms-accent-ink);">
            {{ strtoupper(substr($storeName, 0, 1)) }}
          </div>
          <div>
            <div class="text-base font-extrabold tracking-tight">{{ $storeName }} WMS</div>
            <div class="text-xs font-semibold uppercase tracking-wider" style="color: var(--wms-muted);">Terminal Operasional Gudang</div>
          </div>
        </div>

        <div class="space-y-3">
          <span class="inline-block px-3 py-1 rounded-md font-code text-[11px] font-bold uppercase tracking-wider" style="background-color: var(--wms-accent); color: var(--wms-accent-ink);">
            NativePHP + Handheld Ready
          </span>
          <h1 class="text-2xl font-extrabold tracking-tight leading-snug">Pemindai Stok, Inbound PO, &amp; Picking Pesanan</h1>
          <p class="text-xs leading-relaxed" style="color: var(--wms-muted);">
            Terhubung langsung dengan server ModernGrosir (<span class="font-code">/api/warehouse/*</span>) untuk pencatatan stok fisik, penerimaan barang, mutasi antar cabang, dan verifikasi picking.
          </p>
        </div>

        <ul class="space-y-2.5 text-xs font-semibold">
          <li class="flex items-center gap-2"><span style="color: var(--wms-ok-text);">■</span> Cek Barcode Multi-Satuan &amp; Batch FEFO</li>
          <li class="flex items-center gap-2"><span style="color: var(--wms-ok-text);">■</span> Stock Opname dengan Konversi Satuan Dasar</li>
          <li class="flex items-center gap-2"><span style="color: var(--wms-ok-text);">■</span> Terima PO &amp; Mutasi Gudang Terintegrasi</li>
          <li class="flex items-center gap-2"><span style="color: var(--wms-ok-text);">■</span> Verifikasi Barcode &amp; Selesai Picking Outbound</li>
        </ul>
      </div>

      <div class="pt-6 mt-6 border-t text-[11px] font-code" style="border-color: var(--wms-divider); color: var(--wms-muted);">
        Akses hanya untuk peran Admin / Warehouse / Izin Inventory
      </div>
    </div>

    <!-- Right Login Form -->
    <div class="md:col-span-7 p-6 sm:p-9 flex flex-col justify-center space-y-6">
      <div>
        <h2 class="text-xl font-extrabold tracking-tight">Masuk ke Akun Gudang</h2>
        <p class="text-xs mt-1" style="color: var(--wms-muted);">Masukkan email dan kata sandi staf gudang atau admin toko.</p>
      </div>

      @if($errors->any())
        <div class="rounded-lg border-2 px-4 py-3 text-xs font-bold" style="background-color: var(--wms-err-bg); border-color: var(--wms-err-border); color: var(--wms-err-text);">
          @foreach($errors->all() as $err)
            <div>✕ {{ $err }}</div>
          @endforeach
        </div>
      @endif

      <form method="POST" action="{{ route('login.post') }}" class="space-y-4">
        @csrf
        <div class="space-y-1.5">
          <label for="email" class="block text-xs font-bold uppercase tracking-wider" style="color: var(--wms-muted);">Email Staf</label>
          <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus placeholder="staf.gudang@grosera.id"
                 class="flat-input w-full rounded-lg px-3.5 py-3 text-sm font-semibold">
        </div>

        <div class="space-y-1.5">
          <label for="password" class="block text-xs font-bold uppercase tracking-wider" style="color: var(--wms-muted);">Kata Sandi</label>
          <input id="password" name="password" type="password" required placeholder="••••••••"
                 class="flat-input w-full rounded-lg px-3.5 py-3 text-sm font-semibold">
        </div>

        <button type="submit" class="flat-btn flat-hover-accent w-full rounded-lg py-3.5 px-4 text-sm font-extrabold uppercase tracking-wide" style="background-color: var(--wms-accent); color: var(--wms-accent-ink);">
          Masuk &amp; Mulai Scan
        </button>
      </form>
    </div>
  </div>
</div>
@endsection
