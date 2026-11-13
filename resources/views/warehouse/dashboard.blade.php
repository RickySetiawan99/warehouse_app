@extends('layouts.app')

@section('title', 'Beranda Operasional')

@section('content')
@php
  $poItems = $inboundPos['data'] ?? [];
  $orderItems = $outboundOrders['data'] ?? [];
  $transferItems = $transfers['data'] ?? [];
  $opnameItems = $opnameHistory['data'] ?? [];
@endphp

<div class="space-y-5">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
      <h1 class="fluid-title font-extrabold tracking-tight">Ringkasan Operasional Gudang</h1>
      <p class="text-xs mt-1" style="color: var(--wms-muted);">Tekan <span class="font-code px-1.5 py-0.5 rounded flat-surface-alt">/</span> untuk fokus pemindai, atau gunakan scanner fisik &amp; kamera.</p>
    </div>

    <form method="GET" action="{{ route('scanner') }}" class="flex items-center gap-2 w-full sm:w-auto">
      <input type="text" name="barcode" data-scan-input placeholder="Scan Barcode / Ketik SKU..." required
             class="flat-input min-w-0 min-h-[48px] rounded-lg px-3.5 py-2.5 text-sm font-code font-bold flex-1 sm:w-64">
      <button type="button" data-camera-scan-btn onclick="window.openCameraScanner(this)"
              class="hidden flat-btn flat-surface-alt min-h-[48px] px-3.5 py-2.5 rounded-lg text-xs font-extrabold items-center justify-center gap-1.5 shrink-0"
              title="Scan barcode dengan kamera" aria-label="Scan barcode dengan kamera">
        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
          <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        <span>Kamera</span>
      </button>
      <button type="submit" class="flat-btn flat-hover-accent min-h-[48px] rounded-lg px-5 py-2.5 text-sm font-extrabold shrink-0" style="background-color: var(--wms-accent); color: var(--wms-accent-ink);">
        Cek
      </button>
    </form>
  </div>

  <!-- KPI Cards -->
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
    <a href="{{ route('inbound') }}" class="flat-surface flat-hover-border rounded-xl p-3 sm:p-4 space-y-1 transition-colors">
      <div class="text-xs font-bold" style="color: var(--wms-muted);">Inbound PO</div>
      <div class="text-2xl sm:text-3xl font-extrabold tabular-nums font-code" style="color: var(--wms-accent-text);">{{ count($poItems) }}</div>
      <div class="text-xs font-semibold" style="color: var(--wms-muted);">Menunggu diterima →</div>
    </a>

    <a href="{{ route('picking') }}" class="flat-surface flat-hover-border rounded-xl p-3 sm:p-4 space-y-1 transition-colors">
      <div class="text-xs font-bold" style="color: var(--wms-muted);">Outbound Picking</div>
      <div class="text-2xl sm:text-3xl font-extrabold tabular-nums font-code" style="color: var(--wms-ok-text);">{{ count($orderItems) }}</div>
      <div class="text-xs font-semibold" style="color: var(--wms-muted);">Pesanan antre muat →</div>
    </a>

    <a href="{{ route('transfers') }}" class="flat-surface flat-hover-border rounded-xl p-3 sm:p-4 space-y-1 transition-colors">
      <div class="text-xs font-bold" style="color: var(--wms-muted);">Mutasi In-Transit</div>
      <div class="text-2xl sm:text-3xl font-extrabold tabular-nums font-code">{{ count($transferItems) }}</div>
      <div class="text-xs font-semibold" style="color: var(--wms-muted);">Siap dikonfirmasi →</div>
    </a>

    <a href="{{ route('opname') }}" class="flat-surface flat-hover-border rounded-xl p-3 sm:p-4 space-y-1 transition-colors">
      <div class="text-xs font-bold" style="color: var(--wms-muted);">Riwayat Opname</div>
      <div class="text-2xl sm:text-3xl font-extrabold tabular-nums font-code">{{ count($opnameItems) }}</div>
      <div class="text-xs font-semibold" style="color: var(--wms-muted);">Hitung fisik stok →</div>
    </a>
  </div>

  <!-- Live Operational Queues -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <!-- Picking Queue -->
    <section class="flat-surface rounded-xl p-4 space-y-3">
      <div class="flex items-center justify-between pb-1">
        <h2 class="text-sm font-extrabold">Antrean Outbound Picking</h2>
        <a href="{{ route('picking') }}" class="text-xs font-bold" style="color: var(--wms-accent-text);">Lihat semua</a>
      </div>

      <div class="space-y-2">
        @forelse(array_slice($orderItems, 0, 5) as $order)
          <a href="{{ route('picking.show', $order['id']) }}" class="flat-surface-alt flat-hover-border rounded-lg p-3 flex items-center justify-between gap-2 block">
            <div class="min-w-0">
              <div class="font-code text-xs font-extrabold">{{ $order['order_number'] }}</div>
              <div class="text-xs font-semibold truncate" style="color: var(--wms-muted);">{{ $order['customer_name'] }} · {{ $order['total_items'] }} item</div>
            </div>
            <span class="font-code text-xs font-bold px-2 py-1 rounded" style="background-color: var(--wms-surface); border: 1px solid var(--wms-divider);">{{ $order['status'] }}</span>
          </a>
        @empty
          <p class="text-xs py-2.5 text-center" style="color: var(--wms-muted);">Belum ada pesanan siap picking.</p>
        @endforelse
      </div>
    </section>

    <!-- Inbound PO Queue -->
    <section class="flat-surface rounded-xl p-4 space-y-3">
      <div class="flex items-center justify-between pb-1">
        <h2 class="text-sm font-extrabold">PO Siap Terima</h2>
        <a href="{{ route('inbound') }}" class="text-xs font-bold" style="color: var(--wms-accent-text);">Lihat semua</a>
      </div>

      <div class="space-y-2">
        @forelse(array_slice($poItems, 0, 5) as $po)
          <a href="{{ route('inbound.show', $po['id']) }}" class="flat-surface-alt flat-hover-border rounded-lg p-3 flex items-center justify-between gap-2 block">
            <div class="min-w-0">
              <div class="font-code text-xs font-extrabold">{{ $po['po_number'] }}</div>
              <div class="text-xs font-semibold truncate" style="color: var(--wms-muted);">{{ $po['supplier_name'] }} · {{ $po['total_items'] }} item</div>
            </div>
            <span class="font-code text-xs font-bold px-2 py-1 rounded" style="background-color: var(--wms-surface); border: 1px solid var(--wms-divider);">{{ $po['status'] }}</span>
          </a>
        @empty
          <p class="text-xs py-2.5 text-center" style="color: var(--wms-muted);">Belum ada PO terbuka.</p>
        @endforelse
      </div>
    </section>

    <!-- Transfers In-Transit Queue -->
    <section class="flat-surface rounded-xl p-4 space-y-3">
      <div class="flex items-center justify-between pb-1">
        <h2 class="text-sm font-extrabold">Mutasi Dalam Perjalanan</h2>
        <a href="{{ route('transfers') }}" class="text-xs font-bold" style="color: var(--wms-accent-text);">Lihat semua</a>
      </div>

      <div class="space-y-2">
        @forelse(array_slice($transferItems, 0, 5) as $trf)
          <a href="{{ route('transfers.show', $trf['id']) }}" class="flat-surface-alt flat-hover-border rounded-lg p-3 flex items-center justify-between gap-2 block">
            <div class="min-w-0">
              <div class="font-code text-xs font-extrabold">{{ $trf['transfer_number'] }}</div>
              <div class="text-xs font-semibold truncate" style="color: var(--wms-muted);">{{ $trf['from_warehouse']['name'] ?? 'Asal' }} → {{ $trf['to_warehouse']['name'] ?? 'Tujuan' }}</div>
            </div>
            <span class="font-code text-xs font-bold px-2 py-1 rounded" style="background-color: var(--wms-surface); border: 1px solid var(--wms-divider);">{{ $trf['total_items'] }} item</span>
          </a>
        @empty
          <p class="text-xs py-2.5 text-center" style="color: var(--wms-muted);">Tidak ada mutasi dalam perjalanan.</p>
        @endforelse
      </div>
    </section>
  </div>
</div>
@endsection
