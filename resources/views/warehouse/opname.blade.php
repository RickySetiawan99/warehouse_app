@extends('layouts.app')

@section('title', 'Stock Opname')

@section('content')
@php
  $warehouses = session('warehouses', []);
  $historyItems = $history['data'] ?? [];
@endphp

<div class="space-y-5">
  <div>
    <h1 class="fluid-title font-extrabold tracking-tight">Stock Opname &amp; Penyesuaian Fisik</h1>
    <p class="text-xs mt-1" style="color: var(--wms-muted);">Scan barang, pilih satuan hitung (Pcs/Dus), dan masukkan jumlah fisik. Hasil diajukan untuk persetujuan Admin.</p>
  </div>

  <!-- Barcode Lookup Form -->
  <section class="flat-surface rounded-xl p-4 sm:p-5 space-y-3">
    <form method="GET" action="{{ route('opname') }}" class="flex flex-col sm:flex-row gap-2.5">
      <input type="text" name="barcode" value="{{ $barcode }}" data-scan-input placeholder="Scan Barcode / Ketik SKU untuk mulai opname..." required
             class="flat-input rounded-lg px-4 py-3 text-sm font-code font-bold flex-1">
      <button type="submit" class="flat-btn flat-hover-accent rounded-lg px-5 py-3 text-xs font-extrabold uppercase tracking-wide shrink-0" style="background-color: var(--wms-accent); color: var(--wms-accent-ink);">
        Muat Produk
      </button>
    </form>

    @if($scanError)
      <div class="rounded-lg border px-4 py-3 text-xs font-bold" style="background-color: var(--wms-err-bg); border-color: var(--wms-err-border); color: var(--wms-err-text);">
        ✕ {{ $scanError }}
      </div>
    @endif
  </section>

  @if(session('opname_result'))
    @php $opRes = session('opname_result'); @endphp
    <section class="rounded-xl border-2 p-4 space-y-2" style="background-color: rgba(16, 185, 129, 0.12); border-color: var(--wms-ok);">
      <div class="text-xs font-extrabold uppercase tracking-wider" style="color: var(--wms-ok-text);">Ringkasan Pengajuan Opname</div>
      <div class="text-sm font-extrabold">{{ $opRes['product_name'] ?? '-' }} ({{ $opRes['warehouse_name'] ?? '-' }})</div>
      <div class="grid grid-cols-3 gap-2 text-xs font-code">
        <div>Stok Sistem: <strong>{{ $opRes['system_stock'] ?? 0 }}</strong></div>
        <div>Stok Fisik: <strong>{{ $opRes['physical_stock'] ?? 0 }}</strong></div>
        <div>Selisih: <strong>{{ ($opRes['difference'] ?? 0) > 0 ? '+' . $opRes['difference'] : ($opRes['difference'] ?? 0) }}</strong></div>
      </div>
    </section>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
    <!-- Opname Input Form -->
    <section class="lg:col-span-6 flat-surface rounded-xl p-5 space-y-4">
      <h2 class="text-sm font-extrabold uppercase tracking-wider border-b pb-2.5" style="border-color: var(--wms-divider);">Form Hitung Fisik</h2>

      @if($scannedProduct)
        @php
          $product = $scannedProduct['product'] ?? [];
          $baseUnit = $scannedProduct['base_unit'] ?? [];
          $scannedUnit = $scannedProduct['scanned_unit'] ?? [];
          $units = $scannedProduct['units'] ?? [];
          $systemStock = (int) ($scannedProduct['selected_warehouse_stock'] ?? $scannedProduct['total_stock'] ?? 0);
          $targetWarehouseId = $warehouseId ?? ($warehouses[0]['id'] ?? null);
        @endphp

        <div class="flat-surface-alt rounded-lg p-3.5 space-y-1">
          <div class="font-code text-xs font-extrabold" style="color: var(--wms-accent-text);">{{ $product['sku'] ?? '' }}</div>
          <div class="text-base font-extrabold">{{ $product['name'] ?? '' }}</div>
          <div class="text-xs" style="color: var(--wms-muted);">
            Stok sistem tercatat: <strong class="font-code tabular-nums">{{ $systemStock }} {{ $baseUnit['name'] ?? 'Pcs' }}</strong>
          </div>
        </div>

        <form method="POST" action="{{ route('opname.store') }}" class="space-y-4">
          @csrf
          <input type="hidden" name="product_id" value="{{ $product['id'] ?? '' }}">

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="space-y-1">
              <label for="opname_warehouse_id" class="block text-xs font-bold uppercase tracking-wider" style="color: var(--wms-muted);">Gudang</label>
              <select id="opname_warehouse_id" name="warehouse_id" required class="flat-input w-full rounded-lg px-3 py-2.5 text-sm font-bold">
                @foreach($warehouses as $wh)
                  <option value="{{ $wh['id'] }}" @selected((int) old('warehouse_id', $targetWarehouseId) === (int) $wh['id'])>{{ $wh['name'] }}</option>
                @endforeach
              </select>
            </div>

            <div class="space-y-1">
              <label for="opname_unit_id" class="block text-xs font-bold uppercase tracking-wider" style="color: var(--wms-muted);">Satuan Hitung</label>
              <select id="opname_unit_id" name="unit_id" class="flat-input w-full rounded-lg px-3 py-2.5 text-sm font-bold" onchange="window.updateOpnameDiff()">
                @foreach($units as $unit)
                  @php
                    $uid = $unit['unit_id'] ?? $unit['id'];
                    $ratio = $unit['conversion_ratio'] ?? 1;
                    $isDefault = ($scannedUnit['name'] ?? '') === ($unit['name'] ?? '');
                  @endphp
                  <option value="{{ $uid }}" data-ratio="{{ $ratio }}" @selected((int) old('unit_id', $isDefault ? $uid : 0) === (int) $uid)>
                    {{ $unit['name'] }} (×{{ $ratio }} {{ $baseUnit['name'] ?? '' }})
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 items-end">
            <div class="space-y-1">
              <label for="physical_qty" class="block text-xs font-bold uppercase tracking-wider" style="color: var(--wms-muted);">Jumlah Fisik</label>
              <input id="physical_qty" name="physical_qty" type="number" step="0.01" min="0" required value="{{ old('physical_qty') }}"
                     oninput="window.updateOpnameDiff()"
                     placeholder="0"
                     class="flat-input w-full rounded-lg px-3.5 py-3 text-lg font-code font-extrabold">
            </div>

            <div class="flat-surface-alt rounded-lg p-3 text-xs space-y-0.5">
              <div class="font-bold uppercase tracking-wider text-[10px]" style="color: var(--wms-muted);">Estimasi Selisih</div>
              <div id="opname-diff-preview" data-system-stock="{{ $systemStock }}" class="font-code text-base font-extrabold">0 {{ $baseUnit['name'] ?? 'Pcs' }}</div>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="space-y-1">
              <label for="batch_number" class="block text-xs font-bold uppercase tracking-wider" style="color: var(--wms-muted);">No. Batch (Opsional)</label>
              <input id="batch_number" name="batch_number" type="text" value="{{ old('batch_number') }}" placeholder="BCH-001"
                     class="flat-input w-full rounded-lg px-3 py-2 text-sm font-code">
            </div>

            <div class="space-y-1">
              <label for="expiration_date" class="block text-xs font-bold uppercase tracking-wider" style="color: var(--wms-muted);">Tgl Kadaluarsa (Opsional)</label>
              <input id="expiration_date" name="expiration_date" type="date" value="{{ old('expiration_date') }}"
                     class="flat-input w-full rounded-lg px-3 py-2 text-sm font-code">
            </div>
          </div>

          <div class="space-y-1">
            <label for="reason" class="block text-xs font-bold uppercase tracking-wider" style="color: var(--wms-muted);">Catatan / Alasan</label>
            <input id="reason" name="reason" type="text" value="{{ old('reason') }}" placeholder="Contoh: Stock opname rutin rak A"
                   class="flat-input w-full rounded-lg px-3 py-2.5 text-sm">
          </div>

          <button type="submit" class="flat-btn w-full rounded-lg py-3.5 px-4 text-sm font-extrabold uppercase tracking-wide" style="background-color: var(--wms-ok); color: var(--wms-ok-ink);">
            Ajukan Hasil Opname
          </button>
        </form>
      @else
        <div class="py-10 text-center space-y-2">
          <div class="text-sm font-bold">Belum ada produk dipilih</div>
          <p class="text-xs" style="color: var(--wms-muted);">Masukkan atau pindai barcode di kolom atas untuk membuka form hitung fisik produk.</p>
        </div>
      @endif
    </section>

    <!-- Opname History List -->
    <section class="lg:col-span-6 flat-surface rounded-xl p-5 space-y-4">
      <div class="flex items-center justify-between border-b pb-2.5" style="border-color: var(--wms-divider);">
        <h2 class="text-sm font-extrabold uppercase tracking-wider">Riwayat Pengajuan Opname</h2>
        <span class="font-code text-xs font-bold" style="color: var(--wms-muted);">{{ count($historyItems) }} catatan</span>
      </div>

      <div class="space-y-2.5">
        @forelse($historyItems as $row)
          @php
            $diff = (int) ($row['quantity_change'] ?? 0);
          @endphp
          <div class="flat-surface-alt rounded-lg p-3.5 space-y-1.5">
            <div class="flex items-start justify-between gap-2">
              <div class="min-w-0">
                <div class="text-sm font-extrabold truncate">{{ $row['product_name'] ?? '-' }}</div>
                <div class="font-code text-[11px]" style="color: var(--wms-muted);">{{ $row['sku'] ?? '-' }} · {{ $row['warehouse_name'] ?? '-' }}</div>
              </div>
              <span class="font-code text-[11px] font-bold uppercase px-2 py-0.5 rounded" style="background-color: var(--wms-surface); border: 1px solid var(--wms-divider);">{{ $row['status'] ?? 'pending' }}</span>
            </div>

            <div class="flex items-center justify-between text-xs font-code pt-1">
              <span style="color: var(--wms-muted);">Sistem {{ $row['quantity_before'] ?? 0 }} → Fisik {{ $row['quantity_after'] ?? 0 }} {{ $row['base_unit'] ?? '' }}</span>
              <span class="font-extrabold" style="color: {{ $diff < 0 ? 'var(--wms-err-text)' : 'var(--wms-ok-text)' }};">
                {{ $diff > 0 ? '+' . $diff : $diff }}
              </span>
            </div>
          </div>
        @empty
          <p class="text-xs py-8 text-center" style="color: var(--wms-muted);">Belum ada riwayat pengajuan stock opname.</p>
        @endforelse
      </div>
    </section>
  </div>
</div>

@push('scripts')
<script>
  window.updateOpnameDiff = function () {
    var qtyEl = document.getElementById('physical_qty');
    var unitEl = document.getElementById('opname_unit_id');
    var preview = document.getElementById('opname-diff-preview');
    if (!qtyEl || !unitEl || !preview) return;

    var qty = parseFloat(qtyEl.value || '0');
    var opt = unitEl.options[unitEl.selectedIndex];
    var ratio = parseFloat((opt && opt.getAttribute('data-ratio')) || '1');
    var sys = parseFloat(preview.getAttribute('data-system-stock') || '0');
    var physBase = Math.round(qty * ratio);
    var diff = physBase - sys;

    preview.textContent = (diff > 0 ? '+' : '') + diff + ' (Fisik: ' + physBase + ')';
    preview.style.color = diff < 0 ? 'var(--wms-err-text)' : (diff > 0 ? 'var(--wms-ok-text)' : 'var(--wms-text)');
  };
</script>
@endpush
@endsection
