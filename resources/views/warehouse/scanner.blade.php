@extends('layouts.app')

@section('title', 'Cek Barcode & Stok FEFO')

@section('content')
<div class="space-y-5">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
      <h1 class="fluid-title font-extrabold tracking-tight">Pemindai Barcode Cepat</h1>
      <p class="text-xs mt-1" style="color: var(--wms-muted);">Scan barcode satuan, ketik SKU, atau cari kode produk untuk melihat stok gudang &amp; kadaluarsa batch FEFO.</p>
    </div>
  </div>

  <!-- Barcode Scanner Input Card -->
  <section class="flat-surface rounded-xl p-4 sm:p-5 space-y-3">
    <form method="GET" action="{{ route('scanner') }}" class="flex flex-col sm:flex-row gap-3">
      <input type="text" name="barcode" value="{{ $barcode }}" data-scan-input autofocus required
             placeholder="Arahkan scanner ke barcode atau ketik SKU..."
             class="flat-input min-w-0 min-h-[48px] rounded-lg px-4 py-3 text-base font-code font-bold flex-1">
      <div class="flex items-center gap-2 shrink-0">
        <button type="button" data-camera-scan-btn onclick="window.openCameraScanner(this)"
                class="hidden flat-btn flat-surface-alt min-h-[48px] px-4 py-3 rounded-lg text-sm font-extrabold items-center justify-center gap-2 shrink-0"
                title="Scan barcode dengan kamera" aria-label="Scan barcode dengan kamera">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
          </svg>
          <span>Kamera</span>
        </button>
        <button type="submit" class="flat-btn flat-hover-accent min-h-[48px] rounded-lg px-6 py-3 text-sm font-extrabold flex-1 sm:flex-initial shrink-0" style="background-color: var(--wms-accent); color: var(--wms-accent-ink);">
          Scan / Cari Barang
        </button>
      </div>
    </form>
  </section>

  @if($scanError)
    <div class="rounded-xl border-2 p-5 text-sm font-bold" style="background-color: var(--wms-err-bg); border-color: var(--wms-err-border); color: var(--wms-err-text);">
      ✕ {{ $scanError }}
    </div>
  @elseif($scanResult)
    @php
      $product = $scanResult['product'] ?? [];
      $baseUnit = $scanResult['base_unit'] ?? [];
      $scannedUnit = $scanResult['scanned_unit'] ?? [];
      $units = $scanResult['units'] ?? [];
      $stockByWh = $scanResult['stock_by_warehouse'] ?? [];
      $batches = $scanResult['batches'] ?? [];
    @endphp

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
      <!-- Product Identity & Units -->
      <div class="lg:col-span-7 space-y-4">
        <section class="flat-surface rounded-xl p-5 space-y-4">
          <div class="flex flex-wrap items-start justify-between gap-3 border-b pb-4" style="border-color: var(--wms-divider);">
            <div class="space-y-1">
              <div class="flex items-center gap-2 flex-wrap">
                <span class="font-code text-xs font-extrabold px-2.5 py-1 rounded" style="background-color: var(--wms-accent); color: var(--wms-accent-ink);">{{ $product['sku'] ?? '-' }}</span>
                <span class="text-xs font-bold px-2.5 py-1 rounded flat-surface-alt">{{ $product['category'] ?? 'Umum' }}</span>
              </div>
              <h2 class="text-xl font-extrabold tracking-tight pt-1">{{ $product['name'] ?? '-' }}</h2>
              <p class="text-xs font-semibold" style="color: var(--wms-muted);">
                Satuan dipindai: <span class="font-code font-extrabold">{{ $scannedUnit['name'] ?? '-' }}</span>
                (isi {{ $scannedUnit['conversion_ratio'] ?? 1 }} {{ $baseUnit['name'] ?? 'Pcs' }})
              </p>
            </div>

            <a href="{{ route('opname', ['barcode' => $barcode]) }}" class="flat-btn min-h-[44px] flex items-center rounded-lg px-4 py-2.5 text-xs font-extrabold" style="background-color: var(--wms-ok); color: var(--wms-ok-ink);">
              Hitung Opname Produk Ini →
            </a>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div class="flat-surface-alt rounded-lg p-3.5">
              <div class="text-xs font-bold" style="color: var(--wms-muted);">Total Stok Semua Gudang</div>
              <div class="text-2xl font-extrabold font-code tabular-nums mt-1">{{ $scanResult['total_stock'] ?? 0 }} <span class="text-xs font-bold">{{ $baseUnit['name'] ?? '' }}</span></div>
            </div>
            <div class="flat-surface-alt rounded-lg p-3.5">
              <div class="text-xs font-bold" style="color: var(--wms-muted);">Stok Gudang Aktif</div>
              <div class="text-2xl font-extrabold font-code tabular-nums mt-1" style="color: var(--wms-ok-text);">
                {{ $scanResult['selected_warehouse_stock'] ?? $scanResult['total_stock'] ?? 0 }} <span class="text-xs font-bold">{{ $baseUnit['name'] ?? '' }}</span>
              </div>
            </div>
          </div>

          <!-- Units Table -->
          <div class="space-y-2">
            <h3 class="text-xs font-extrabold" style="color: var(--wms-muted);">Daftar Satuan &amp; Barcode</h3>
            <div class="space-y-1.5">
              @foreach($units as $unit)
                <div class="flat-surface-alt rounded-lg px-3.5 py-2.5 flex items-center justify-between gap-2 text-xs">
                  <div>
                    <span class="font-extrabold">{{ $unit['name'] }}</span>
                    <span class="font-code ms-1.5" style="color: var(--wms-muted);">×{{ $unit['conversion_ratio'] }}</span>
                  </div>
                  <div class="font-code font-bold">{{ $unit['barcode'] ?: 'Tanpa Barcode' }}</div>
                </div>
              @endforeach
            </div>
          </div>
        </section>
      </div>

      <!-- Warehouse Breakdown & FEFO Batches -->
      <div class="lg:col-span-5 space-y-4">
        <section class="flat-surface rounded-xl p-5 space-y-3">
          <h3 class="text-xs font-extrabold" style="color: var(--wms-muted);">Stok per Gudang</h3>
          <div class="space-y-2">
            @forelse($stockByWh as $wh)
              <div class="flat-surface-alt rounded-lg px-3.5 py-2.5 flex items-center justify-between text-xs font-bold">
                <span>{{ $wh['warehouse_name'] }}</span>
                <span class="font-code text-sm">{{ $wh['quantity'] }} {{ $baseUnit['name'] ?? '' }}</span>
              </div>
            @empty
              <p class="text-xs" style="color: var(--wms-muted);">Belum ada catatan stok per gudang.</p>
            @endforelse
          </div>
        </section>

        <section class="flat-surface rounded-xl p-5 space-y-3">
          <h3 class="text-xs font-extrabold" style="color: var(--wms-muted);">Batch &amp; Kadaluarsa (FEFO)</h3>
          <div class="space-y-2">
            @forelse($batches as $batch)
              @php
                $isExpired = !empty($batch['is_expired']);
                $daysLeft = $batch['days_until_expiration'] ?? null;
                $isNear = !$isExpired && $daysLeft !== null && $daysLeft <= 30;
              @endphp
              <div class="flat-surface-alt rounded-lg p-3 flex items-center justify-between gap-2 text-xs border" style="border-color: {{ $isExpired ? 'var(--wms-err-border)' : ($isNear ? 'var(--wms-warn-border)' : 'var(--wms-divider)') }};">
                <div>
                  <div class="font-code font-extrabold">{{ $batch['batch_number'] ?: 'TANPA-BATCH' }}</div>
                  <div style="color: var(--wms-muted);">{{ $batch['warehouse_name'] }} · Exp: {{ $batch['expiration_date'] ?: '-' }}</div>
                </div>
                <div class="text-end">
                  <div class="font-code font-extrabold text-sm">{{ $batch['quantity'] }}</div>
                  @if($isExpired)
                    <span class="font-code text-xs font-extrabold" style="color: var(--wms-err-text);">KADALUARSA</span>
                  @elseif($isNear)
                    <span class="font-code text-xs font-extrabold" style="color: var(--wms-warn-text);">{{ $daysLeft }} HARI LAGI</span>
                  @else
                    <span class="font-code text-xs font-extrabold" style="color: var(--wms-ok-text);">AMAN</span>
                  @endif
                </div>
              </div>
            @empty
              <p class="text-xs" style="color: var(--wms-muted);">Tidak ada batch stok aktif.</p>
            @endforelse
          </div>
        </section>
      </div>
    </div>
  @endif
</div>
@endsection
