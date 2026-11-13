@extends('layouts.app')

@section('title', 'Penerimaan Inbound PO')

@section('content')
<div class="space-y-5">
  <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
    <div>
      <h1 class="fluid-title font-extrabold tracking-tight">Penerimaan Barang Inbound (PO)</h1>
      <p class="text-xs mt-1" style="color: var(--wms-muted);">Pilih PO dari daftar, periksa qty &amp; kadaluarsa batch, lalu simpan penerimaan.</p>
    </div>

    <form method="GET" action="{{ route('inbound') }}" class="flex items-center gap-2">
      <input type="text" name="search" value="{{ $search }}" placeholder="Cari No. PO / Supplier..."
             class="flat-input rounded-lg px-3.5 py-2 text-xs font-bold w-56">
      <button type="submit" class="flat-btn flat-surface-alt rounded-lg px-3.5 py-2 text-xs font-extrabold">Cari</button>
    </form>
  </div>

  @if(session('price_alerts'))
    <div class="rounded-xl border-2 p-4 space-y-1.5" style="background-color: var(--wms-warn-bg); border-color: var(--wms-warn-border); color: var(--wms-warn-text);">
      <div class="text-xs font-extrabold uppercase tracking-wider">Peringatan Kenaikan Harga Beli</div>
      @foreach(session('price_alerts') as $alert)
        <div class="text-xs font-semibold">• {{ $alert['product_name'] ?? 'Produk' }}: {{ $alert['message'] ?? 'Harga beli berubah.' }}</div>
      @endforeach
    </div>
  @endif

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
    <!-- Left PO Queue -->
    <section class="lg:col-span-4 flat-surface rounded-xl p-4 space-y-3">
      <h2 class="text-xs font-extrabold uppercase tracking-wider border-b pb-2" style="border-color: var(--wms-divider);">Daftar PO Terbuka ({{ count($poList) }})</h2>
      <div class="space-y-2">
        @forelse($poList as $po)
          @php $isSelected = (string) ($activePo['id'] ?? $selectedId) === (string) $po['id']; @endphp
          <a href="{{ route('inbound.show', $po['id']) }}"
             class="flat-btn rounded-lg p-3 block border"
             style="background-color: {{ $isSelected ? 'var(--wms-accent)' : 'var(--wms-surface-alt)' }}; color: {{ $isSelected ? 'var(--wms-accent-ink)' : 'var(--wms-text)' }}; border-color: {{ $isSelected ? 'var(--wms-accent)' : 'var(--wms-divider)' }};">
            <div class="flex items-center justify-between gap-2">
              <span class="font-code text-xs font-extrabold">{{ $po['po_number'] }}</span>
              <span class="font-code text-[10px] font-bold uppercase px-1.5 py-0.5 rounded bg-black/15">{{ $po['status'] }}</span>
            </div>
            <div class="text-xs font-bold mt-1">{{ $po['supplier_name'] }}</div>
            <div class="text-[11px] opacity-80 mt-0.5">{{ $po['warehouse_name'] }} · {{ $po['total_items'] }} item</div>
          </a>
        @empty
          <p class="text-xs py-6 text-center" style="color: var(--wms-muted);">Tidak ada PO terbuka.</p>
        @endforelse
      </div>
    </section>

    <!-- Right PO Receiving Form -->
    <section class="lg:col-span-8 flat-surface rounded-xl p-5 space-y-4">
      @if($activePo)
        <div class="flex flex-wrap items-start justify-between gap-3 border-b pb-3.5" style="border-color: var(--wms-divider);">
          <div>
            <div class="font-code text-xs font-extrabold" style="color: var(--wms-accent-text);">{{ $activePo['po_number'] }}</div>
            <h2 class="text-lg font-extrabold tracking-tight">{{ $activePo['supplier']['name'] ?? 'Supplier' }}</h2>
            <p class="text-xs" style="color: var(--wms-muted);">Tujuan: {{ $activePo['warehouse']['name'] ?? 'Gudang' }} · Status: <span class="font-code font-bold uppercase">{{ $activePo['status'] }}</span></p>
          </div>
          <div class="flex items-center gap-2">
            <input type="text" id="inbound-quick-sku" data-scan-input placeholder="Scan SKU di PO..."
                   onkeydown="if(event.key==='Enter'){event.preventDefault();window.highlightInboundSku(this.value);}"
                   class="flat-input rounded-lg px-3 py-2 text-xs font-code font-bold w-44">
          </div>
        </div>

        <form method="POST" action="{{ route('inbound.receive', $activePo['id']) }}" class="space-y-4">
          @csrf
          <div class="flex items-center gap-3">
            <label for="received_date" class="text-xs font-bold uppercase tracking-wider" style="color: var(--wms-muted);">Tanggal Terima</label>
            <input id="received_date" name="received_date" type="date" value="{{ old('received_date', now()->toDateString()) }}"
                   class="flat-input rounded-lg px-3 py-1.5 text-xs font-code font-bold">
          </div>

          <div class="space-y-3">
            @foreach($activePo['items'] ?? [] as $idx => $item)
              @php $remaining = max(0, (float) ($item['remaining_qty'] ?? 0)); @endphp
              <div class="flat-surface-alt rounded-lg p-3.5 space-y-2.5 inbound-item-row" data-sku="{{ strtoupper((string) ($item['sku'] ?? '')) }}">
                <input type="hidden" name="items[{{ $idx }}][item_id]" value="{{ $item['id'] }}">
                <input type="hidden" name="items[{{ $idx }}][product_id]" value="{{ $item['product_id'] }}">

                <div class="flex flex-wrap items-center justify-between gap-2">
                  <div>
                    <div class="font-code text-[11px] font-bold" style="color: var(--wms-accent-text);">{{ $item['sku'] ?? '-' }}</div>
                    <div class="text-sm font-extrabold">{{ $item['product_name'] ?? '-' }}</div>
                  </div>
                  <div class="font-code text-xs text-right">
                    <div>Pesan: <strong>{{ $item['ordered_qty'] ?? 0 }}</strong> {{ $item['unit_name'] ?? '' }}</div>
                    <div style="color: var(--wms-muted);">Sisa: <strong>{{ $remaining }}</strong></div>
                  </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-2.5">
                  <div>
                    <label class="block text-[10px] font-bold uppercase mb-1" style="color: var(--wms-muted);">Qty Diterima ({{ $item['unit_name'] ?? '' }})</label>
                    <input type="number" step="0.01" min="0" name="items[{{ $idx }}][received_qty]" value="{{ old("items.{$idx}.received_qty", $remaining) }}"
                           class="flat-input w-full rounded-lg px-3 py-2 text-sm font-code font-extrabold">
                  </div>
                  <div>
                    <label class="block text-[10px] font-bold uppercase mb-1" style="color: var(--wms-muted);">No. Batch</label>
                    <input type="text" name="items[{{ $idx }}][batch_number]" value="{{ old("items.{$idx}.batch_number") }}" placeholder="BCH-..."
                           class="flat-input w-full rounded-lg px-3 py-2 text-sm font-code">
                  </div>
                  <div>
                    <label class="block text-[10px] font-bold uppercase mb-1" style="color: var(--wms-muted);">Kadaluarsa</label>
                    <input type="date" name="items[{{ $idx }}][expiration_date]" value="{{ old("items.{$idx}.expiration_date", $item['default_expiration_date'] ?? '') }}"
                           class="flat-input w-full rounded-lg px-3 py-2 text-sm font-code">
                  </div>
                </div>
              </div>
            @endforeach
          </div>

          <button type="submit" class="flat-btn w-full rounded-lg py-3.5 px-4 text-sm font-extrabold uppercase tracking-wide" style="background-color: var(--wms-ok); color: var(--wms-ok-ink);">
            Konfirmasi &amp; Terima Barang PO
          </button>
        </form>
      @else
        <div class="py-12 text-center text-sm font-bold" style="color: var(--wms-muted);">Pilih Purchase Order di sebelah kiri untuk memproses penerimaan barang.</div>
      @endif
    </section>
  </div>
</div>

@push('scripts')
<script>
  window.highlightInboundSku = function (raw) {
    var code = (raw || '').trim().toUpperCase();
    if (!code) return;
    var row = document.querySelector('.inbound-item-row[data-sku="' + code + '"]');
    if (row) {
      row.style.borderColor = 'var(--wms-accent)';
      row.scrollIntoView({ behavior: 'smooth', block: 'center' });
      var qtyInput = row.querySelector('input[type="number"]');
      if (qtyInput) qtyInput.focus();
      window.playBeep('ok');
      window.showToast('SKU ' + code + ' ditemukan di PO.', 'ok');
    } else {
      window.playBeep('err');
      window.showToast('SKU ' + code + ' tidak ada pada PO aktif.', 'err');
    }
  };
</script>
@endpush
@endsection
