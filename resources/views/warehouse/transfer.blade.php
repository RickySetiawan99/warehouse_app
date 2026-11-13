@extends('layouts.app')

@section('title', 'Penerimaan Mutasi Gudang')

@section('content')
<div class="space-y-5">
  <div>
    <h1 class="fluid-title font-extrabold tracking-tight">Terima Mutasi Stok Antar Gudang</h1>
    <p class="text-xs mt-1" style="color: var(--wms-muted);">Verifikasi jumlah barang datang dari surat jalan transfer in-transit.</p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
    <!-- Transfer Queue -->
    <section class="lg:col-span-4 flat-surface rounded-xl p-4 space-y-3">
      <h2 class="text-sm font-extrabold">Mutasi Dalam Perjalanan ({{ count($transferList) }})</h2>
      <div class="space-y-2">
        @forelse($transferList as $trf)
          @php $isSelected = (string) ($activeTransfer['id'] ?? $selectedId) === (string) $trf['id']; @endphp
          <a href="{{ route('transfers.show', $trf['id']) }}"
             class="flat-btn min-h-[56px] rounded-lg p-3 block border"
             style="background-color: {{ $isSelected ? 'var(--wms-accent)' : 'var(--wms-surface-alt)' }}; color: {{ $isSelected ? 'var(--wms-accent-ink)' : 'var(--wms-text)' }}; border-color: {{ $isSelected ? 'var(--wms-accent)' : 'var(--wms-divider)' }};">
            <div class="flex items-center justify-between gap-2">
              <span class="font-code text-xs font-extrabold">{{ $trf['transfer_number'] }}</span>
              <span class="font-code text-xs font-bold px-1.5 py-0.5 rounded bg-black/15">{{ $trf['total_items'] ?? 0 }} item</span>
            </div>
            <div class="text-xs font-bold mt-1">{{ $trf['from_warehouse']['name'] ?? 'Asal' }} → {{ $trf['to_warehouse']['name'] ?? 'Tujuan' }}</div>
          </a>
        @empty
          <p class="text-xs py-6 text-center" style="color: var(--wms-muted);">Tidak ada mutasi stok in-transit.</p>
        @endforelse
      </div>
    </section>

    <!-- Active Transfer Detail & Receive -->
    <section class="lg:col-span-8 flat-surface rounded-xl p-5 space-y-4">
      @if($activeTransfer)
        <div class="border-b pb-3.5" style="border-color: var(--wms-divider);">
          <div class="font-code text-xs font-extrabold" style="color: var(--wms-accent-text);">{{ $activeTransfer['transfer_number'] }}</div>
          <h2 class="text-lg font-extrabold tracking-tight">
            {{ $activeTransfer['from_warehouse']['name'] ?? 'Gudang Asal' }} → {{ $activeTransfer['to_warehouse']['name'] ?? 'Gudang Tujuan' }}
          </h2>
          @if(!empty($activeTransfer['notes']))
            <p class="text-xs mt-1" style="color: var(--wms-muted);">Catatan Kirim: {{ $activeTransfer['notes'] }}</p>
          @endif
        </div>

        <form method="POST" action="{{ route('transfers.receive', $activeTransfer['id']) }}" class="space-y-4">
          @csrf
          <div class="space-y-3">
            @foreach($activeTransfer['items'] ?? [] as $item)
              @php $sentQty = (float) ($item['quantity'] ?? 0); @endphp
              <div class="flat-surface-alt rounded-lg p-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="min-w-0">
                  <div class="font-code text-xs font-bold" style="color: var(--wms-accent-text);">{{ $item['sku'] ?? '-' }}</div>
                  <div class="text-sm font-extrabold truncate">{{ $item['product_name'] ?? '-' }}</div>
                  <div class="text-xs font-code" style="color: var(--wms-muted);">Dikirim: <strong>{{ $sentQty }} {{ $item['unit_name'] ?? 'Pcs' }}</strong></div>
                </div>

                <div class="sm:w-44 shrink-0">
                  <label class="block text-xs font-bold mb-1" style="color: var(--wms-muted);">Qty Diterima ({{ $item['unit_name'] ?? '' }})</label>
                  <input type="number" step="0.01" min="0" name="received[{{ $item['id'] }}]" value="{{ old("received.{$item['id']}", $sentQty) }}" required
                         class="flat-input min-h-[48px] w-full rounded-lg px-3 py-2 text-sm font-code font-extrabold">
                </div>
              </div>
            @endforeach
          </div>

          <div class="space-y-1.5">
            <label for="receive_notes" class="block text-xs font-bold" style="color: var(--wms-muted);">Catatan penerimaan (wajib diisi jika ada selisih/rusak)</label>
            <input id="receive_notes" name="receive_notes" type="text" value="{{ old('receive_notes') }}" placeholder="Contoh: Semua barang diterima utuh / 1 dus penyok"
                   class="flat-input min-h-[48px] w-full rounded-lg px-3.5 py-2.5 text-sm">
          </div>

          <button type="submit" class="flat-btn w-full min-h-[50px] rounded-lg py-3.5 px-4 text-sm font-extrabold" style="background-color: var(--wms-ok); color: var(--wms-ok-ink);">
            Konfirmasi Terima Mutasi Stok
          </button>
        </form>
      @else
        <div class="py-12 text-center text-sm font-bold" style="color: var(--wms-muted);">Pilih dokumen mutasi in-transit di sebelah kiri.</div>
      @endif
    </section>
  </div>
</div>
@endsection
