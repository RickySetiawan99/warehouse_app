@extends('layouts.app')

@section('title', 'Picking & Verifikasi Outbound')

@section('content')
@php
  $matchedItem = session('matched_item');
@endphp

<div class="space-y-5">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
      <h1 class="fluid-title font-extrabold tracking-tight">Outbound Picking &amp; Verifikasi Barcode</h1>
      <p class="text-xs mt-1" style="color: var(--wms-muted);">Pindai barcode setiap barang yang diambil agar bebas salah kirim, lalu tandai siap muat.</p>
    </div>

    <form method="GET" action="{{ route('picking') }}" class="flex items-center gap-2">
      <input type="text" name="search" value="{{ $search }}" placeholder="Cari Order / Reseller..."
             class="flat-input min-h-[44px] rounded-lg px-3.5 py-2 text-xs font-bold w-56">
      <button type="submit" class="flat-btn flat-surface-alt min-h-[44px] rounded-lg px-4 py-2 text-xs font-extrabold">Cari</button>
    </form>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
    <!-- Order Queue -->
    <section class="lg:col-span-4 flat-surface rounded-xl p-4 space-y-3">
      <h2 class="text-sm font-extrabold">Antrean Pesanan ({{ count($orderList) }})</h2>
      <div class="space-y-2">
        @forelse($orderList as $order)
          @php $isSelected = (string) ($activeOrder['id'] ?? $selectedId) === (string) $order['id']; @endphp
          <a href="{{ route('picking.show', $order['id']) }}"
             class="flat-btn min-h-[56px] rounded-lg p-3 block border"
             style="background-color: {{ $isSelected ? 'var(--wms-accent)' : 'var(--wms-surface-alt)' }}; color: {{ $isSelected ? 'var(--wms-accent-ink)' : 'var(--wms-text)' }}; border-color: {{ $isSelected ? 'var(--wms-accent)' : 'var(--wms-divider)' }};">
            <div class="flex items-center justify-between gap-2">
              <span class="font-code text-xs font-extrabold">{{ $order['order_number'] }}</span>
              <span class="font-code text-xs font-bold px-1.5 py-0.5 rounded bg-black/15">{{ $order['status'] }}</span>
            </div>
            <div class="text-xs font-bold mt-1">{{ $order['customer_name'] }}</div>
            <div class="text-xs opacity-80 mt-0.5">{{ $order['warehouse_name'] }} · {{ $order['total_items'] }} item</div>
          </a>
        @empty
          <p class="text-xs py-6 text-center" style="color: var(--wms-muted);">Tidak ada pesanan outbound aktif.</p>
        @endforelse
      </div>
    </section>

    <!-- Active Order Checklist & Verify -->
    <section class="lg:col-span-8 flat-surface rounded-xl p-5 space-y-5">
      @if($activeOrder)
        @php
          $items = $activeOrder['items'] ?? [];
          $totalItems = count($items);
          $verifiedCount = collect($items)->filter(fn ($i) => in_array($i['id'], $verifiedItemIds, true))->count();
          $pct = $totalItems > 0 ? round(($verifiedCount / $totalItems) * 100) : 0;
          $isPacked = ($activeOrder['status'] ?? '') === 'packing';
        @endphp

        <div class="flex flex-wrap items-start justify-between gap-3 border-b pb-3.5" style="border-color: var(--wms-divider);">
          <div>
            <div class="font-code text-xs font-extrabold" style="color: var(--wms-accent-text);">{{ $activeOrder['order_number'] }}</div>
            <h2 class="text-lg font-extrabold tracking-tight">{{ $activeOrder['customer_name'] ?? 'Pelanggan' }}</h2>
            <p class="text-xs" style="color: var(--wms-muted);">Gudang: {{ $activeOrder['warehouse']['name'] ?? 'Gudang' }} · Status: <span class="font-code font-bold">{{ $activeOrder['status'] }}</span></p>
          </div>
          <div class="text-end font-code">
            <div class="text-xs font-extrabold">{{ $verifiedCount }} / {{ $totalItems }} Terverifikasi</div>
            <div class="text-xs" style="color: var(--wms-ok-text);">{{ $pct }}% siap muat</div>
          </div>
        </div>

        <!-- Barcode Verify Form -->
        <form method="POST" action="{{ route('picking.verify', $activeOrder['id']) }}" class="flex flex-col sm:flex-row gap-3">
          @csrf
          <input type="text" name="barcode" data-scan-input autofocus required placeholder="Scan barcode barang yang diambil untuk verifikasi..."
                 class="flat-input min-w-0 min-h-[48px] rounded-lg px-4 py-3 text-sm font-code font-bold flex-1">
          <div class="flex items-center gap-2 shrink-0">
            <button type="button" data-camera-scan-btn onclick="window.openCameraScanner(this)"
                    class="hidden flat-btn flat-surface-alt min-h-[48px] px-4 py-3 rounded-lg text-xs font-extrabold items-center justify-center gap-1.5 shrink-0"
                    title="Scan barcode dengan kamera" aria-label="Scan barcode dengan kamera">
              <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
              <span>Kamera</span>
            </button>
            <button type="submit" class="flat-btn flat-hover-accent min-h-[48px] rounded-lg px-5 py-3 text-xs font-extrabold flex-1 sm:flex-initial shrink-0" style="background-color: var(--wms-accent); color: var(--wms-accent-ink);">
              Cek Cocok
            </button>
          </div>
        </form>

        <!-- Picking Item Checklist -->
        <div class="space-y-2.5">
          @foreach($items as $item)
            @php
              $verified = in_array($item['id'], $verifiedItemIds, true) || ($matchedItem && (int) ($matchedItem['id'] ?? 0) === (int) $item['id']);
            @endphp
            <div class="flat-surface-alt rounded-lg p-3.5 flex flex-wrap items-center justify-between gap-3 border-2" style="border-color: {{ $verified ? 'var(--wms-ok)' : 'var(--wms-divider)' }};">
              <div class="space-y-0.5 min-w-0">
                <div class="flex items-center gap-2">
                  <span class="font-code text-xs font-bold" style="color: var(--wms-accent-text);">{{ $item['sku'] ?? '-' }}</span>
                  @if($verified)
                    <span class="font-code text-xs font-extrabold px-2 py-0.5 rounded" style="background-color: var(--wms-ok); color: var(--wms-ok-ink);">Terverifikasi ✓</span>
                  @endif
                </div>
                <div class="text-sm font-extrabold truncate">{{ $item['product_name'] ?? '-' }}</div>
                <div class="text-xs font-code" style="color: var(--wms-muted);">Barcode: {{ implode(', ', $item['valid_barcodes'] ?? []) }}</div>
              </div>

              <div class="text-end font-code shrink-0">
                <div class="text-base font-extrabold tabular-nums">{{ $item['quantity'] ?? 0 }} {{ $item['unit_name'] ?? 'Pcs' }}</div>
                <div class="text-xs" style="color: var(--wms-muted);">{{ $item['qty_base'] ?? 0 }} dasar</div>
              </div>
            </div>
          @endforeach
        </div>

        <!-- Complete Picking Form -->
        @if(! $isPacked)
          <form method="POST" action="{{ route('picking.complete', $activeOrder['id']) }}" class="border-t pt-4 space-y-3" style="border-color: var(--wms-divider);">
            @csrf
            <div class="space-y-1.5">
              <label for="picking_notes" class="block text-xs font-bold" style="color: var(--wms-muted);">Catatan packing / jumlah koli (opsional)</label>
              <input id="picking_notes" name="notes" type="text" placeholder="Contoh: 3 Koli lakban merah, siap muat di Dock 2"
                     class="flat-input min-h-[48px] w-full rounded-lg px-3.5 py-2.5 text-sm">
            </div>

            <button type="submit" class="flat-btn w-full min-h-[50px] rounded-lg py-3.5 px-4 text-sm font-extrabold" style="background-color: var(--wms-ok); color: var(--wms-ok-ink);">
              Selesaikan Picking &amp; Tandai Siap Muat
            </button>
          </form>
        @else
          <div class="rounded-lg border-2 p-3.5 text-xs font-extrabold text-center" style="background-color: rgba(16, 185, 129, 0.14); border-color: var(--wms-ok); color: var(--wms-ok-text);">
            ✓ Pesanan Sudah Berstatus Packing (Siap Muat / Surat Jalan)
          </div>
        @endif
      @else
        <div class="py-12 text-center text-sm font-bold" style="color: var(--wms-muted);">Pilih pesanan di sebelah kiri untuk memulai verifikasi picking.</div>
      @endif
    </section>
  </div>
</div>
@endsection
