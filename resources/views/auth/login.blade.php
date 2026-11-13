@extends('layouts.app')

@section('title', 'Masuk Staf Gudang')

@section('content')
@php
  $tenant = $store ?? session('tenant', []);
  $storeName = $tenant['clean_name'] ?? $tenant['name'] ?? 'Grosera';
@endphp

<div class="min-h-dvh flex flex-col justify-between">
  <!-- Zone 1: Top Utility Bar -->
  <header class="w-full max-w-5xl mx-auto px-5 sm:px-8 pt-5 pb-3 flex items-center justify-between gap-4">
    <div class="flex items-center gap-3 min-w-0">
      <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0" style="background-color: var(--wms-accent); color: #0F172A;" aria-hidden="true">
        <svg class="w-6 h-6" viewBox="82 65 172 179">
          <path fill-rule="evenodd" fill="currentColor" d="M158.88 170.88 L157.62 171.12 L154.75 172.25 L144.88 177.00 L137.62 180.12 L134.62 181.75 L132.88 183.75 L132.12 185.75 L132.00 186.62 L132.00 224.25 L132.88 227.00 L133.88 228.62 L135.50 230.25 L140.88 233.50 L146.50 236.38 L152.00 239.75 L153.38 240.38 L155.50 241.75 L159.88 244.12 L162.25 244.88 L165.38 245.00 L167.12 244.62 L169.00 243.88 L174.00 241.00 L178.12 239.00 L189.38 232.88 L191.50 231.12 L192.50 229.62 L193.12 228.25 L193.75 226.00 L193.88 189.88 L193.50 188.38 L192.88 187.12 L191.62 185.75 L190.12 184.75 L170.00 174.38 L165.00 171.62 L162.75 170.88ZM244.38 140.75 L242.25 140.00 L238.62 139.50 L201.38 139.38 L201.25 139.25 L191.62 139.25 L186.00 139.00 L181.50 140.00 L177.12 142.12 L169.25 146.88 L161.50 151.12 L160.50 152.38 L160.38 153.88 L160.88 154.75 L162.00 155.62 L172.25 160.88 L177.50 163.25 L181.00 165.25 L193.50 171.50 L196.88 173.38 L202.50 176.00 L203.25 176.50 L204.00 177.38 L204.38 178.50 L204.38 222.50 L205.00 224.38 L206.12 225.25 L207.50 225.38 L209.62 224.50 L220.25 218.12 L224.75 215.75 L234.88 209.75 L243.50 205.00 L246.25 202.50 L247.88 200.25 L249.00 198.12 L249.88 195.88 L250.62 191.62 L250.88 150.00 L250.12 147.00 L248.62 144.12 L246.62 142.12ZM254.25 105.25 L252.62 104.00 L240.12 97.25 L229.50 91.12 L223.88 88.12 L215.50 83.12 L207.25 78.75 L185.75 66.50 L184.50 66.00 L180.75 65.25 L177.75 65.12 L173.12 66.00 L170.62 67.12 L168.00 68.88 L151.00 78.88 L135.12 88.62 L129.12 92.00 L126.50 93.75 L117.25 99.25 L110.00 104.00 L99.00 110.50 L97.00 111.88 L91.25 115.00 L89.88 116.00 L87.75 118.00 L85.75 120.62 L84.25 123.38 L83.00 127.25 L82.50 131.62 L82.50 135.62 L82.38 135.75 L82.50 157.38 L82.25 158.50 L82.25 160.88 L82.38 161.00 L82.38 169.38 L82.50 169.50 L82.50 190.62 L83.12 194.75 L84.12 197.62 L85.12 199.62 L86.75 201.88 L88.00 203.12 L91.38 205.62 L92.88 206.25 L94.75 207.38 L100.50 210.25 L110.75 216.12 L113.12 217.25 L119.88 221.00 L121.12 221.12 L121.75 220.88 L122.25 220.38 L122.75 219.25 L122.75 212.25 L122.88 212.12 L122.75 143.38 L123.25 141.25 L124.25 139.75 L125.38 138.62 L127.12 137.38 L141.38 128.75 L145.75 125.88 L151.88 122.25 L156.00 119.50 L177.25 106.38 L179.75 105.62 L181.25 105.50 L182.88 105.75 L185.25 106.50 L191.88 110.12 L196.88 113.12 L201.75 115.62 L206.12 118.25 L215.38 123.25 L217.12 124.00 L220.75 124.88 L223.00 124.88 L226.50 124.00 L228.75 122.88 L240.25 116.12 L253.25 109.00 L254.62 107.75 L254.88 107.12 L254.88 106.38Z"/>
        </svg>
      </div>
      <div class="min-w-0">
        <div class="text-sm font-extrabold tracking-tight truncate">{{ $storeName }} WMS</div>
        <div class="text-xs truncate" style="color: var(--wms-muted);">Terminal Operasional Gudang</div>
      </div>
    </div>

    <button type="button" onclick="window.toggleTheme()" aria-label="Ganti tema gelap atau terang"
            class="flat-btn min-h-[44px] px-3.5 rounded-lg flex items-center gap-2 text-xs font-bold shrink-0"
            style="background-color: var(--wms-surface); color: var(--wms-text);">
      <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
      </svg>
      <span>Tema</span>
    </button>
  </header>

  <!-- Zone 2: Main Focal Stage -->
  <div class="w-full max-w-5xl mx-auto px-5 sm:px-8 py-6 sm:py-10">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-12 items-center">
      <!-- Leading Context Column -->
      <div class="lg:col-span-6 space-y-6">
        <div class="space-y-3">
          <h1 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold tracking-tight leading-tight">
            Masuk ke Akun Gudang
          </h1>
          <p class="text-sm sm:text-base leading-relaxed max-w-prose" style="color: var(--wms-muted);">
            Gunakan akun staf gudang atau admin toko untuk mengakses pemindai stok, penerimaan PO, dan verifikasi surat jalan.
          </p>
        </div>

        <dl class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
          <div class="p-3.5 rounded-lg space-y-1" style="background-color: var(--wms-surface);">
            <dt class="text-xs font-extrabold" style="color: var(--wms-accent-text);">Scan &amp; FEFO</dt>
            <dd class="text-xs leading-snug" style="color: var(--wms-muted);">Cek stok rak &amp; batch kadaluarsa</dd>
          </div>
          <div class="p-3.5 rounded-lg space-y-1" style="background-color: var(--wms-surface);">
            <dt class="text-xs font-extrabold" style="color: var(--wms-ok-text);">Inbound &amp; Picking</dt>
            <dd class="text-xs leading-snug" style="color: var(--wms-muted);">Terima PO &amp; verifikasi muat</dd>
          </div>
          <div class="p-3.5 rounded-lg space-y-1" style="background-color: var(--wms-surface);">
            <dt class="text-xs font-extrabold">Opname &amp; Mutasi</dt>
            <dd class="text-xs leading-snug" style="color: var(--wms-muted);">Hitung fisik &amp; transfer cabang</dd>
          </div>
        </dl>
      </div>

      <!-- Trailing Form Surface -->
      <section class="lg:col-span-6 xl:col-span-5 xl:col-start-8 p-6 sm:p-8 rounded-xl space-y-6" style="background-color: var(--wms-surface);" aria-labelledby="login-form-heading">
        <div>
          <h2 id="login-form-heading" class="text-lg font-extrabold tracking-tight">Kredensial Staf</h2>
          <p class="text-xs mt-1" style="color: var(--wms-muted);">Akses khusus peran Warehouse, Admin, atau Izin Inventory.</p>
        </div>

        @if($errors->any())
          <div role="alert" class="rounded-lg px-4 py-3 text-sm font-medium space-y-1" style="background-color: var(--wms-err-bg); color: var(--wms-err-text);">
            @foreach($errors->all() as $err)
              <div>{{ $err }}</div>
            @endforeach
          </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}" class="space-y-6">
          @csrf
          <div class="space-y-1.5">
            <label for="email" class="block text-sm font-semibold">Email</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                   autocomplete="email" enterkeyhint="next" placeholder="staf.gudang@grosera.id"
                   class="flat-input w-full min-h-[48px] rounded-lg px-3.5 py-2.5 text-sm font-semibold">
          </div>

          <div class="space-y-1.5">
            <label for="password" class="block text-sm font-semibold">Kata sandi</label>
            <div class="relative">
              <input id="password" name="password" type="password" required
                     autocomplete="current-password" enterkeyhint="go" placeholder="••••••••"
                     class="flat-input w-full min-h-[48px] rounded-lg ps-3.5 pe-24 py-2.5 text-sm font-semibold">
              <button type="button" onclick="window.toggleLoginPassword(this)"
                      aria-label="Tampilkan kata sandi" aria-pressed="false"
                      class="flat-btn absolute inset-y-1.5 end-1.5 px-3 rounded-md text-xs font-bold"
                      style="background-color: var(--wms-surface-alt); color: var(--wms-muted);">
                Lihat
              </button>
            </div>
          </div>

          <button type="submit"
                  class="flat-btn flat-hover-accent w-full min-h-[50px] rounded-lg px-5 py-3 text-sm font-extrabold"
                  style="background-color: var(--wms-accent); color: var(--wms-accent-ink);">
            Masuk ke Terminal Gudang
          </button>
        </form>
      </section>
    </div>
  </div>

  <!-- Zone 3: Bottom Grounding Bar -->
  <footer class="w-full max-w-5xl mx-auto px-5 sm:px-8 pb-5 pt-3 flex flex-wrap items-center justify-between gap-3 text-xs" style="color: var(--wms-muted);">
    <span>Server API: {{ config('services.moderngrosir.api_url') }}</span>
    <span>Tekan / di dalam aplikasi untuk fokus ke pemindai barcode</span>
  </footer>
</div>

@push('scripts')
<script>
  window.toggleLoginPassword = function (btn) {
    var input = document.getElementById('password');
    if (!input) return;
    var show = input.type === 'password';
    input.type = show ? 'text' : 'password';
    btn.textContent = show ? 'Tutup' : 'Lihat';
    btn.setAttribute('aria-pressed', show ? 'true' : 'false');
    btn.setAttribute('aria-label', show ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi');
  };
</script>
@endpush
@endsection
