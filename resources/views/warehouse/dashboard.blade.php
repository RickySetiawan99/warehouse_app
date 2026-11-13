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
      <p class="text-xs mt-1" style="color: var(--wms-muted);">Tekan <span class="font-code px-1.5 py-0.5 rounded flat-surface-alt">/</span> kapan saja untuk langsung fokus ke pemindai barcode.</p>
    </div>

    <form method="GET" action="{{ route('scanner') }}" class="flex items-center gap-2 w-full sm:w-auto">
      <input type="text" name="barcode" data-scan-input placeholder="Scan Barcode / Ketik SKU..." required
             class="flat-input rounded-lg px-3.5 py-2.5 text-sm font-code font-bold flex-1 sm:w-64">
      <button type="submit" class="flat-btn flat-hover-accent rounded-lg px-4 py-2.5 text-sm font-extrabold shrink-0" style="background-color: var(--wms-accent); color: var(--wms-accent-ink);">
        Cek
      </button>
    </form>
  </div>

  <!-- KPI Cards -->
  <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
    <a href="{{ route('inbound') }}" class="flat-surface flat-hover-border rounded-xl p-4 space-y-2 transition-colors">
      <div class="text-xs font-bold uppercase tracking-wider" style="color: var(--wms-muted);">Inbound PO</div>
      <div class="text-3xl font-extrabold tabular-nums font-code" style="color: var(--wms-accent-text);">{{ count($poItems) }}</div>
      <div class="text-xs font-semibold" style="color: var(--wms-muted);">Menunggu diterima →</div>
    </a>

    <a href="{{ route('picking') }}" class="flat-surface flat-hover-border rounded-xl p-4 space-y-2 transition-colors">
      <div class="text-xs font-bold uppercase tracking-wider" style="color: var(--wms-muted);">Outbound Picking</div>
      <div class="text-3xl font-extrabold tabular-nums font-code" style="color: var(--wms-ok-text);">{{ count($orderItems) }}</div>
      <div class="text-xs font-semibold" style="color: var(--wms-muted);">Pesanan antre muat →</div>
    </a>

    <a href="{{ route('transfers') }}" class="flat-surface flat-hover-border rounded-xl p-4 space-y-2 transition-colors">
      <div class="text-xs font-bold uppercase tracking-wider" style="color: var(--wms-muted);">Mutasi In-Transit</div>
      <div class="text-3xl font-extrabold tabular-nums font-code">{{ count($transferItems) }}</div>
      <div class="text-xs font-semibold" style="color: var(--wms-muted);">Siap dikonfirmasi →</div>
    </a>

    <a href="{{ route('opname') }}" class="flat-surface flat-hover-border rounded-xl p-4 space-y-2 transition-colors">
      <div class="text-xs font-bold uppercase tracking-wider" style="color: var(--wms-muted);">Riwayat Opname</div>
      <div class="text-3xl font-extrabold tabular-nums font-code">{{ count($opnameItems) }}</div>
      <div class="text-xs font-semibold" style="color: var(--wms-muted);">Hitung fisik stok →</div>
    </a>
  </div>

  <!-- Live Operational Queues -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <!-- Picking Queue -->
    <section class="flat-surface rounded-xl p-4 space-y-3">
      <div class="flex items-center justify-between border-b pb-2.5" style="border-color: var(--wms-divider);">
        <h2 class="text-sm font-extrabold uppercase tracking-wider">Antrean Outbound Picking</h2>
        <a href="{{ route('picking') }}" class="text-xs font-bold" style="color: var(--wms-accent-text);">Lihat semua</a>
      </div>

      <div class="space-y-2">
        @forelse(array_slice($orderItems, 0, 5) as $order)
          <a href="{{ route('picking.show', $order['id']) }}" class="flat-surface-alt flat-hover-border rounded-lg p-3 flex items-center justify-between gap-2 block">
            <div class="min-w-0">
              <div class="font-code text-xs font-extrabold">{{ $order['order_number'] }}</div>
              <div class="text-xs font-semibold truncate" style="color: var(--wms-muted);">{{ $order['customer_name'] }} · {{ $order['total_items'] }} item</div>
            </div>
            <span class="font-code text-[11px] font-bold uppercase px-2 py-1 rounded" style="background-color: var(--wms-surface); border: 1px solid var(--wms-divider);">{{ $order['status'] }}</span>
          </a>
        @empty
          <p class="text-xs py-4 text-center" style="color: var(--wms-muted);">Belum ada pesanan siap picking.</p>
        @endforelse
      </div>
    </section>

    <!-- Inbound PO Queue -->
    <section class="flat-surface rounded-xl p-4 space-y-3">
      <div class="flex items-center justify-between border-b pb-2.5" style="border-color: var(--wms-divider);">
        <h2 class="text-sm font-extrabold uppercase tracking-wider">PO Siap Terima</h2>
        <a href="{{ route('inbound') }}" class="text-xs font-bold" style="color: var(--wms-accent-text);">Lihat semua</a>
      </div>

      <div class="space-y-2">
        @forelse(array_slice($poItems, 0, 5) as $po)
          <a href="{{ route('inbound.show', $po['id']) }}" class="flat-surface-alt flat-hover-border rounded-lg p-3 flex items-center justify-between gap-2 block">
            <div class="min-w-0">
              <div class="font-code text-xs font-extrabold">{{ $po['po_number'] }}</div>
              <div class="text-xs font-semibold truncate" style="color: var(--wms-muted);">{{ $po['supplier_name'] }} · {{ $po['total_items'] }} item</div>
            </div>
            <span class="font-code text-[11px] font-bold uppercase px-2 py-1 rounded" style="background-color: var(--wms-surface); border: 1px solid var(--wms-divider);">{{ $po['status'] }}</span>
          </a>
        @empty
          <p class="text-xs py-4 text-center" style="color: var(--wms-muted);">Belum ada PO terbuka.</p>
        @endforelse
      </div>
    </section>

    <!-- Transfers In-Transit Queue -->
    <section class="flat-surface rounded-xl p-4 space-y-3">
      <div class="flex items-center justify-between border-b pb-2.5" style="border-color: var(--wms-divider);">
        <h2 class="text-sm font-extrabold uppercase tracking-wider">Mutasi Dalam Perjalanan</h2>
        <a href="{{ route('transfers') }}" class="text-xs font-bold" style="color: var(--wms-accent-text);">Lihat semua</a>
      </div>

      <div class="space-y-2">
        @forelse(array_slice($transferItems, 0, 5) as $trf)
          <a href="{{ route('transfers.show', $trf['id']) }}" class="flat-surface-alt flat-hover-border rounded-lg p-3 flex items-center justify-between gap-2 block">
            <div class="min-w-0">
              <div class="font-code text-xs font-extrabold">{{ $trf['transfer_number'] }}</div>
              <div class="text-xs font-semibold truncate" style="color: var(--wms-muted);">{{ $trf['from_warehouse']['name'] ?? 'Asal' }} → {{ $trf['to_warehouse']['name'] ?? 'Tujuan' }}</div>
            </div>
            <span class="font-code text-[11px] font-bold uppercase px-2 py-1 rounded" style="background-color: var(--wms-surface); border: 1px solid var(--wms-divider);">{{ $trf['total_items'] }} item</span>
          </a>
        @empty
          <p class="text-xs py-4 text-center" style="color: var(--wms-muted);">Tidak ada mutasi dalam perjalanan.</p>
        @endforelse
      </div>
    </section>
  </div>
</div>
@endsection
