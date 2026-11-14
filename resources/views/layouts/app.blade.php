<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover, interactive-widget=resizes-content">
  <title>@yield('title', 'Grosera WMS') — Gudang Mobile</title>
  <script>
    (function () {
      try {
        var saved = localStorage.getItem('wms_theme');
        if (saved === 'light' || saved === 'dark') {
          document.documentElement.setAttribute('data-theme', saved);
        }
      } catch (e) {}
    })();
  </script>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@500;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --ease-out: cubic-bezier(0.23, 1, 0.32, 1);
      --wms-accent: #FF783F;
      --wms-accent-hover: #E5622B;
      --wms-accent-ink: #0F172A;
      --wms-ok: #10B981;
      --wms-ok-ink: #0F172A;
    }

    html[data-theme="dark"] {
      --wms-bg: #0F172A;
      --wms-surface: #182235;
      --wms-surface-alt: #223049;
      --wms-input: #0B111E;
      --wms-divider: #2A3954;
      --wms-border: #64748B;
      --wms-text: #F8FAFC;
      --wms-muted: #CBD5E1;
      --wms-accent-text: #FF9F6E;
      --wms-ok-text: #34D399;
      --wms-err-bg: #991B1B;
      --wms-err-text: #FCA5A5;
      --wms-err-border: #EF4444;
      --wms-warn-bg: #78350F;
      --wms-warn-text: #FDE68A;
      --wms-warn-border: #F59E0B;
    }

    html[data-theme="light"] {
      --wms-bg: #F1F5F9;
      --wms-surface: #FFFFFF;
      --wms-surface-alt: #E2E8F0;
      --wms-input: #F8FAFC;
      --wms-divider: #CBD5E1;
      --wms-border: #64748B;
      --wms-text: #0F172A;
      --wms-muted: #475569;
      --wms-accent-text: #C2410C;
      --wms-ok-text: #047857;
      --wms-err-bg: #FEE2E2;
      --wms-err-text: #B91C1C;
      --wms-err-border: #DC2626;
      --wms-warn-bg: #FEF3C7;
      --wms-warn-text: #92400E;
      --wms-warn-border: #D97706;
    }

    * { box-sizing: border-box; }

    html {
      -webkit-tap-highlight-color: transparent;
    }

    html, body {
      margin: 0;
      padding: 0;
      overflow-x: clip;
      background-color: var(--wms-bg);
      color: var(--wms-text);
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
      -webkit-font-smoothing: antialiased;
    }

    .font-code {
      font-family: 'JetBrains Mono', ui-monospace, monospace;
      font-variant-numeric: tabular-nums;
    }

    .tabular-nums { font-variant-numeric: tabular-nums; }

    .fluid-title {
      font-size: clamp(1.125rem, 2.2vw, 1.5rem);
      line-height: 1.25;
    }

    .flat-surface {
      background-color: var(--wms-surface);
      border: 1px solid var(--wms-divider);
    }

    .flat-surface-alt {
      background-color: var(--wms-surface-alt);
      border: 1px solid var(--wms-divider);
    }

    .flat-input {
      background-color: var(--wms-input);
      border: 1.5px solid var(--wms-border);
      color: var(--wms-text);
      font-size: 16px;
      scroll-margin-bottom: 140px;
      transition: border-color 150ms var(--ease-out), background-color 150ms var(--ease-out);
    }

    .flat-input:focus-visible {
      outline: 2px solid var(--wms-accent);
      outline-offset: 2px;
      border-color: var(--wms-accent);
    }

    .flat-btn {
      transition: transform 130ms var(--ease-out), background-color 130ms ease, color 130ms ease, border-color 130ms ease;
      user-select: none;
      -webkit-tap-highlight-color: transparent;
      touch-action: manipulation;
    }

    .flat-btn:active { transform: scale(0.97); }

    .flat-btn:focus-visible, .flat-focus:focus-visible {
      outline: 2px solid var(--wms-accent);
      outline-offset: 2px;
    }

    @media (hover: hover) and (pointer: fine) {
      .flat-hover-surface:hover { background-color: var(--wms-surface-alt); }
      .flat-hover-border:hover { border-color: var(--wms-accent); }
      .flat-hover-accent:hover { background-color: var(--wms-accent-hover); }
    }

    .flat-toast {
      position: fixed;
      top: max(12px, env(safe-area-inset-top));
      inset-inline-start: 16px;
      inset-inline-end: 16px;
      max-width: 460px;
      margin-inline: auto;
      z-index: 80;
      opacity: 0;
      pointer-events: none;
      transform: translateY(-120%) scale(0.96);
      transition: transform 220ms var(--ease-out), opacity 180ms var(--ease-out);
    }

    .flat-toast[data-open="true"] {
      opacity: 1;
      pointer-events: auto;
      transform: translateY(0) scale(1);
    }

    .mob-pill {
      transition: background-color 170ms var(--ease-out), color 170ms var(--ease-out);
    }

    .mob-nav-link[aria-current="page"] .mob-pill {
      background-color: var(--wms-accent);
      color: var(--wms-accent-ink);
    }

    .mob-nav-link[aria-current="page"] .mob-label {
      color: var(--wms-accent-text);
      font-weight: 800;
    }

    .mob-nav-link .icon-solid { display: none; }
    .mob-nav-link[aria-current="page"] .icon-solid { display: block; }
    .mob-nav-link[aria-current="page"] .icon-outline { display: none; }

    .rail-nav-link[aria-current="page"],
    .tab-nav-link[aria-current="page"],
    [data-theme-btn][aria-pressed="true"] {
      background-color: var(--wms-accent);
      color: var(--wms-accent-ink);
      border-color: var(--wms-accent);
    }

    .mobile-content-pad { padding-bottom: calc(6.75rem + env(safe-area-inset-bottom)) !important; }
    @media (min-width: 768px) { .mobile-content-pad { padding-bottom: 2.5rem !important; } }
    .mobile-bottom-nav { padding-bottom: max(0.5rem, env(safe-area-inset-bottom)); }

    .flat-sheet-backdrop {
      position: fixed;
      inset: 0;
      z-index: 60;
      background-color: rgba(15, 23, 42, 0.75);
      opacity: 0;
      pointer-events: none;
      transition: opacity 180ms var(--ease-out);
    }

    .flat-sheet-backdrop[data-open="true"] {
      opacity: 1;
      pointer-events: auto;
    }

    .flat-sheet-panel {
      position: fixed;
      inset-inline: 0;
      bottom: 0;
      z-index: 65;
      background-color: var(--wms-surface);
      border-top: 2px solid var(--wms-divider);
      padding: 16px 16px calc(20px + env(safe-area-inset-bottom));
      transform: translateY(100%);
      transition: transform 220ms var(--ease-out);
    }

    .flat-sheet-panel[data-open="true"] {
      transform: translateY(0);
    }

    .camera-modal-backdrop {
      position: fixed;
      inset: 0;
      z-index: 70;
      background-color: rgba(15, 23, 42, 0.82);
      opacity: 0;
      pointer-events: none;
      transition: opacity 180ms var(--ease-out);
    }

    .camera-modal-backdrop[data-open="true"] {
      opacity: 1;
      pointer-events: auto;
    }

    .camera-reticle {
      border: 2px solid var(--wms-accent);
      box-shadow: 0 0 0 9999px rgba(15, 23, 42, 0.45);
    }
  </style>
</head>
<body class="min-h-dvh flex flex-col">

  <!-- Interruptible Toast -->
  <div id="flat-toast" class="flat-toast rounded-lg px-4 py-3 border-2 flex items-center gap-3" role="status" aria-live="polite" data-open="false">
    <span id="toast-icon" class="shrink-0 font-bold text-base">●</span>
    <span id="toast-msg" class="text-sm font-bold flex-1 leading-snug">Notifikasi</span>
    <button type="button" onclick="window.hideToast()" class="flat-btn px-2.5 py-1.5 text-xs font-extrabold rounded opacity-80 hover:opacity-100">Tutup</button>
  </div>

  @if(session()->has('api_token'))
    @php
      $user = session('user', []);
      $warehouses = session('warehouses', []);
      $activeWarehouseId = session('active_warehouse_id');
      $activeWarehouse = collect($warehouses)->first(fn ($w) => (int) ($w['id'] ?? 0) === (int) $activeWarehouseId);
      $activeWarehouseName = $activeWarehouse['name'] ?? 'Semua Gudang';

      $navItems = [
        ['route' => 'dashboard', 'label' => 'Beranda', 'short' => 'Beranda', 'pattern' => 'dashboard'],
        ['route' => 'scanner', 'label' => 'Cek Barcode', 'short' => 'Scan', 'pattern' => 'scanner'],
        ['route' => 'opname', 'label' => 'Stock Opname', 'short' => 'Opname', 'pattern' => 'opname*'],
        ['route' => 'inbound', 'label' => 'Terima PO', 'short' => 'Inbound', 'pattern' => 'inbound*'],
        ['route' => 'picking', 'label' => 'Picking & Packing', 'short' => 'Picking', 'pattern' => 'picking*'],
        ['route' => 'transfers', 'label' => 'Mutasi Gudang', 'short' => 'Mutasi', 'pattern' => 'transfers*'],
      ];

      $isHome = request()->routeIs('dashboard');
      $isSecondaryModule = request()->routeIs('inbound*') || request()->routeIs('picking*') || request()->routeIs('transfers*');
    @endphp

    <!-- Mobile & Tablet Sticky Header Bar -->
    <header class="lg:hidden sticky top-0 z-40 border-b pt-[env(safe-area-inset-top)]" style="background-color: var(--wms-surface); border-color: var(--wms-divider);">
      <div class="px-4 min-h-[58px] flex items-center justify-between gap-2.5">
        <div class="flex items-center gap-3 min-w-0">
          @if($isHome)
            <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0" style="background-color: var(--wms-accent); color: #0F172A;" aria-hidden="true">
              <svg class="w-6 h-6" viewBox="82 65 172 179">
                <path fill-rule="evenodd" fill="currentColor" d="M158.88 170.88 L157.62 171.12 L154.75 172.25 L144.88 177.00 L137.62 180.12 L134.62 181.75 L132.88 183.75 L132.12 185.75 L132.00 186.62 L132.00 224.25 L132.88 227.00 L133.88 228.62 L135.50 230.25 L140.88 233.50 L146.50 236.38 L152.00 239.75 L153.38 240.38 L155.50 241.75 L159.88 244.12 L162.25 244.88 L165.38 245.00 L167.12 244.62 L169.00 243.88 L174.00 241.00 L178.12 239.00 L189.38 232.88 L191.50 231.12 L192.50 229.62 L193.12 228.25 L193.75 226.00 L193.88 189.88 L193.50 188.38 L192.88 187.12 L191.62 185.75 L190.12 184.75 L170.00 174.38 L165.00 171.62 L162.75 170.88ZM244.38 140.75 L242.25 140.00 L238.62 139.50 L201.38 139.38 L201.25 139.25 L191.62 139.25 L186.00 139.00 L181.50 140.00 L177.12 142.12 L169.25 146.88 L161.50 151.12 L160.50 152.38 L160.38 153.88 L160.88 154.75 L162.00 155.62 L172.25 160.88 L177.50 163.25 L181.00 165.25 L193.50 171.50 L196.88 173.38 L202.50 176.00 L203.25 176.50 L204.00 177.38 L204.38 178.50 L204.38 222.50 L205.00 224.38 L206.12 225.25 L207.50 225.38 L209.62 224.50 L220.25 218.12 L224.75 215.75 L234.88 209.75 L243.50 205.00 L246.25 202.50 L247.88 200.25 L249.00 198.12 L249.88 195.88 L250.62 191.62 L250.88 150.00 L250.12 147.00 L248.62 144.12 L246.62 142.12ZM254.25 105.25 L252.62 104.00 L240.12 97.25 L229.50 91.12 L223.88 88.12 L215.50 83.12 L207.25 78.75 L185.75 66.50 L184.50 66.00 L180.75 65.25 L177.75 65.12 L173.12 66.00 L170.62 67.12 L168.00 68.88 L151.00 78.88 L135.12 88.62 L129.12 92.00 L126.50 93.75 L117.25 99.25 L110.00 104.00 L99.00 110.50 L97.00 111.88 L91.25 115.00 L89.88 116.00 L87.75 118.00 L85.75 120.62 L84.25 123.38 L83.00 127.25 L82.50 131.62 L82.50 135.62 L82.38 135.75 L82.50 157.38 L82.25 158.50 L82.25 160.88 L82.38 161.00 L82.38 169.38 L82.50 169.50 L82.50 190.62 L83.12 194.75 L84.12 197.62 L85.12 199.62 L86.75 201.88 L88.00 203.12 L91.38 205.62 L92.88 206.25 L94.75 207.38 L100.50 210.25 L110.75 216.12 L113.12 217.25 L119.88 221.00 L121.12 221.12 L121.75 220.88 L122.25 220.38 L122.75 219.25 L122.75 212.25 L122.88 212.12 L122.75 143.38 L123.25 141.25 L124.25 139.75 L125.38 138.62 L127.12 137.38 L141.38 128.75 L145.75 125.88 L151.88 122.25 L156.00 119.50 L177.25 106.38 L179.75 105.62 L181.25 105.50 L182.88 105.75 L185.25 106.50 L191.88 110.12 L196.88 113.12 L201.75 115.62 L206.12 118.25 L215.38 123.25 L217.12 124.00 L220.75 124.88 L223.00 124.88 L226.50 124.00 L228.75 122.88 L240.25 116.12 L253.25 109.00 L254.62 107.75 L254.88 107.12 L254.88 106.38Z"/>
              </svg>
            </div>
          @else
            <a href="{{ route('dashboard') }}" class="flat-btn flat-surface-alt w-11 h-11 rounded-lg flex items-center justify-center shrink-0" aria-label="Kembali ke Beranda">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
              </svg>
            </a>
          @endif

          <div class="min-w-0">
            <div class="text-sm font-extrabold tracking-tight truncate">@yield('title', 'Grosera WMS')</div>
            <div class="text-xs truncate" style="color: var(--wms-muted);">{{ $activeWarehouseName }} · {{ $user['name'] ?? 'Staf Gudang' }}</div>
          </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
          @if(!empty($warehouses))
            <form method="POST" action="{{ route('warehouse.select') }}" class="hidden sm:block">
              @csrf
              <select name="warehouse_id" onchange="this.form.submit()" aria-label="Pilih Gudang Aktif" class="flat-input rounded-lg px-2.5 py-2 text-xs font-bold max-w-[140px] truncate">
                @foreach($warehouses as $wh)
                  <option value="{{ $wh['id'] }}" @selected((int) $wh['id'] === (int) $activeWarehouseId)>{{ $wh['name'] }}</option>
                @endforeach
              </select>
            </form>
          @endif
          <button type="button" onclick="window.toggleTheme()" class="flat-btn flat-surface-alt w-11 h-11 rounded-lg flex items-center justify-center" title="Ganti Tema" aria-label="Ubah Tema">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
          </button>
          <button type="button" onclick="window.openMobileSheet()" id="mob-top-menu-btn" aria-haspopup="dialog" aria-expanded="false"
                  class="md:hidden flat-btn flat-surface-alt w-11 h-11 rounded-lg flex items-center justify-center" title="Modul & Akun" aria-label="Buka modul gudang dan pengaturan akun">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
            </svg>
          </button>
          <form method="POST" action="{{ route('logout') }}" class="hidden md:block">
            @csrf
            <button type="submit" class="flat-btn flat-surface-alt min-h-[44px] px-3 rounded-lg text-xs font-bold" style="color: var(--wms-err-text);">Keluar</button>
          </form>
        </div>
      </div>

      <!-- Tablet Horizontal Tab Strip (md only) -->
      <nav class="hidden md:flex lg:hidden border-t px-4 py-2 gap-1.5 overflow-x-auto" style="border-color: var(--wms-divider);" aria-label="Navigasi Modul Tablet">
        @foreach($navItems as $item)
          <a href="{{ route($item['route']) }}"
             @if(request()->routeIs($item['pattern'])) aria-current="page" @endif
             class="tab-nav-link flat-btn flat-surface-alt min-h-[42px] px-3.5 py-2 rounded-lg text-xs font-bold whitespace-nowrap flex items-center">
            {{ $item['label'] }}
          </a>
        @endforeach
      </nav>
    </header>

    <div class="flex-1 flex flex-col lg:flex-row">
      <!-- Desktop Left Navigation Rail (lg+) -->
      <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:shrink-0 border-r min-h-dvh sticky top-0 h-dvh" style="background-color: var(--wms-surface); border-color: var(--wms-divider);">
        <div class="p-4 border-b flex items-center gap-3" style="border-color: var(--wms-divider);">
          <div class="w-10 h-10 rounded-lg flex items-center justify-center shrink-0" style="background-color: var(--wms-accent); color: #0F172A;" aria-hidden="true">
            <svg class="w-6 h-6" viewBox="82 65 172 179">
              <path fill-rule="evenodd" fill="currentColor" d="M158.88 170.88 L157.62 171.12 L154.75 172.25 L144.88 177.00 L137.62 180.12 L134.62 181.75 L132.88 183.75 L132.12 185.75 L132.00 186.62 L132.00 224.25 L132.88 227.00 L133.88 228.62 L135.50 230.25 L140.88 233.50 L146.50 236.38 L152.00 239.75 L153.38 240.38 L155.50 241.75 L159.88 244.12 L162.25 244.88 L165.38 245.00 L167.12 244.62 L169.00 243.88 L174.00 241.00 L178.12 239.00 L189.38 232.88 L191.50 231.12 L192.50 229.62 L193.12 228.25 L193.75 226.00 L193.88 189.88 L193.50 188.38 L192.88 187.12 L191.62 185.75 L190.12 184.75 L170.00 174.38 L165.00 171.62 L162.75 170.88ZM244.38 140.75 L242.25 140.00 L238.62 139.50 L201.38 139.38 L201.25 139.25 L191.62 139.25 L186.00 139.00 L181.50 140.00 L177.12 142.12 L169.25 146.88 L161.50 151.12 L160.50 152.38 L160.38 153.88 L160.88 154.75 L162.00 155.62 L172.25 160.88 L177.50 163.25 L181.00 165.25 L193.50 171.50 L196.88 173.38 L202.50 176.00 L203.25 176.50 L204.00 177.38 L204.38 178.50 L204.38 222.50 L205.00 224.38 L206.12 225.25 L207.50 225.38 L209.62 224.50 L220.25 218.12 L224.75 215.75 L234.88 209.75 L243.50 205.00 L246.25 202.50 L247.88 200.25 L249.00 198.12 L249.88 195.88 L250.62 191.62 L250.88 150.00 L250.12 147.00 L248.62 144.12 L246.62 142.12ZM254.25 105.25 L252.62 104.00 L240.12 97.25 L229.50 91.12 L223.88 88.12 L215.50 83.12 L207.25 78.75 L185.75 66.50 L184.50 66.00 L180.75 65.25 L177.75 65.12 L173.12 66.00 L170.62 67.12 L168.00 68.88 L151.00 78.88 L135.12 88.62 L129.12 92.00 L126.50 93.75 L117.25 99.25 L110.00 104.00 L99.00 110.50 L97.00 111.88 L91.25 115.00 L89.88 116.00 L87.75 118.00 L85.75 120.62 L84.25 123.38 L83.00 127.25 L82.50 131.62 L82.50 135.62 L82.38 135.75 L82.50 157.38 L82.25 158.50 L82.25 160.88 L82.38 161.00 L82.38 169.38 L82.50 169.50 L82.50 190.62 L83.12 194.75 L84.12 197.62 L85.12 199.62 L86.75 201.88 L88.00 203.12 L91.38 205.62 L92.88 206.25 L94.75 207.38 L100.50 210.25 L110.75 216.12 L113.12 217.25 L119.88 221.00 L121.12 221.12 L121.75 220.88 L122.25 220.38 L122.75 219.25 L122.75 212.25 L122.88 212.12 L122.75 143.38 L123.25 141.25 L124.25 139.75 L125.38 138.62 L127.12 137.38 L141.38 128.75 L145.75 125.88 L151.88 122.25 L156.00 119.50 L177.25 106.38 L179.75 105.62 L181.25 105.50 L182.88 105.75 L185.25 106.50 L191.88 110.12 L196.88 113.12 L201.75 115.62 L206.12 118.25 L215.38 123.25 L217.12 124.00 L220.75 124.88 L223.00 124.88 L226.50 124.00 L228.75 122.88 L240.25 116.12 L253.25 109.00 L254.62 107.75 L254.88 107.12 L254.88 106.38Z"/>
            </svg>
          </div>
          <div class="min-w-0">
            <div class="text-sm font-extrabold tracking-tight">Grosera WMS</div>
            <div class="text-xs font-semibold truncate" style="color: var(--wms-muted);">Operasional Gudang</div>
          </div>
        </div>

        <div class="p-4 border-b space-y-2" style="border-color: var(--wms-divider);">
          <div class="text-xs font-bold" style="color: var(--wms-muted);">Gudang aktif</div>
          @if(!empty($warehouses))
            <form method="POST" action="{{ route('warehouse.select') }}">
              @csrf
              <select name="warehouse_id" onchange="this.form.submit()" aria-label="Pilih Gudang Aktif" class="flat-input w-full min-h-[42px] rounded-lg px-3 py-2 text-xs font-bold">
                @foreach($warehouses as $wh)
                  <option value="{{ $wh['id'] }}" @selected((int) $wh['id'] === (int) $activeWarehouseId)>{{ $wh['name'] }} ({{ strtoupper($wh['type'] ?? 'GUDANG') }})</option>
                @endforeach
              </select>
            </form>
          @else
            <div class="text-xs font-bold">{{ $activeWarehouseName }}</div>
          @endif
        </div>

        <nav class="p-3 flex-1 space-y-1.5 overflow-y-auto" aria-label="Navigasi Utama Gudang">
          @foreach($navItems as $index => $item)
            <a href="{{ route($item['route']) }}"
               @if(request()->routeIs($item['pattern'])) aria-current="page" @endif
               class="rail-nav-link flat-btn flat-hover-surface flex items-center justify-between min-h-[46px] px-3 py-2.5 rounded-lg text-sm font-bold border border-transparent">
              <span>{{ $item['label'] }}</span>
              <span class="font-code text-xs opacity-70">{{ $index + 1 }}</span>
            </a>
          @endforeach
        </nav>

        <div class="p-4 border-t space-y-3" style="border-color: var(--wms-divider);">
          <div class="flex items-center justify-between gap-2">
            <span class="text-xs font-bold" style="color: var(--wms-muted);">Tema layar</span>
            <div class="flex items-center gap-1.5">
              <button type="button" data-theme-btn="dark" onclick="window.setTheme('dark')" aria-pressed="true" class="flat-btn flat-surface-alt min-h-[38px] px-2.5 py-1 rounded text-xs font-bold border">Gelap</button>
              <button type="button" data-theme-btn="light" onclick="window.setTheme('light')" aria-pressed="false" class="flat-btn flat-surface-alt min-h-[38px] px-2.5 py-1 rounded text-xs font-bold border">Terang</button>
            </div>
          </div>

          <div class="pt-3 border-t flex items-center justify-between gap-2" style="border-color: var(--wms-divider);">
            <div class="min-w-0">
              <div class="text-xs font-extrabold truncate">{{ $user['name'] ?? 'Staf Gudang' }}</div>
              <div class="text-xs truncate" style="color: var(--wms-muted);">{{ $user['email'] ?? '' }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="flat-btn flat-surface-alt min-h-[40px] px-3 py-1.5 rounded-lg text-xs font-extrabold" style="color: var(--wms-err-text);">Keluar</button>
            </form>
          </div>
        </div>
      </aside>

      <!-- Main Operational Workspace -->
      <main class="flex-1 min-w-0 px-4 sm:px-6 lg:px-8 pt-4 sm:pt-6 pb-28 md:pb-10 mobile-content-pad max-w-6xl w-full mx-auto space-y-5">
        @if(session('success'))
          <div role="status" class="rounded-lg border-2 px-4 py-3 text-sm font-bold flex items-start gap-2.5" style="background-color: rgba(16, 185, 129, 0.14); border-color: var(--wms-ok); color: var(--wms-ok-text);">
            <span>✓</span>
            <span>{{ session('success') }}</span>
          </div>
        @endif

        @if($errors->any())
          <div role="alert" class="rounded-lg border-2 px-4 py-3 text-sm font-bold space-y-1" style="background-color: var(--wms-err-bg); border-color: var(--wms-err-border); color: var(--wms-err-text);">
            @foreach($errors->all() as $err)
              <div>✕ {{ $err }}</div>
            @endforeach
          </div>
        @endif

        @yield('content')
      </main>
    </div>

    <!-- Mobile 4-Tab Capsule Bottom Nav (< 768px) -->
    <nav class="md:hidden fixed bottom-0 inset-x-0 z-40 border-t mobile-bottom-nav" style="background-color: var(--wms-surface); border-color: var(--wms-divider);" aria-label="Navigasi Bawah Gudang">
      <div class="grid grid-cols-4 gap-2 px-3 pt-1.5 max-w-md mx-auto">
        <a href="{{ route('dashboard') }}" @if(request()->routeIs('dashboard')) aria-current="page" @endif
           class="mob-nav-link flat-btn min-h-[54px] flex flex-col items-center justify-center gap-1" style="color: var(--wms-muted);">
          <span class="mob-pill w-14 h-8 rounded-full flex items-center justify-center">
            <svg class="icon-outline w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            <svg class="icon-solid w-5 h-5 fill-current" viewBox="0 0 20 20" aria-hidden="true"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
          </span>
          <span class="mob-label text-xs font-semibold leading-none">Beranda</span>
        </a>

        <a href="{{ route('scanner') }}" @if(request()->routeIs('scanner')) aria-current="page" @endif
           class="mob-nav-link flat-btn min-h-[54px] flex flex-col items-center justify-center gap-1" style="color: var(--wms-muted);">
          <span class="mob-pill w-14 h-8 rounded-full flex items-center justify-center">
            <svg class="icon-outline w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 7V5a1 1 0 011-1h2M17 4h2a1 1 0 011 1v2M4 17v2a1 1 0 001 1h2M17 20h2a1 1 0 001-1v-2M7 12h10"/></svg>
            <svg class="icon-solid w-5 h-5 fill-current" viewBox="0 0 24 24" aria-hidden="true"><path d="M3 7V5a2 2 0 012-2h2v2H5v2H3zm14-4h2a2 2 0 012 2v2h-2V5h-2V3zM3 17h2v2h2v2H5a2 2 0 01-2-2v-2zm16 0h2v2a2 2 0 01-2 2h-2v-2h2v-2zM6 11h12v2H6v-2z"/></svg>
          </span>
          <span class="mob-label text-xs font-semibold leading-none">Scan</span>
        </a>

        <a href="{{ route('opname') }}" @if(request()->routeIs('opname*')) aria-current="page" @endif
           class="mob-nav-link flat-btn min-h-[54px] flex flex-col items-center justify-center gap-1" style="color: var(--wms-muted);">
          <span class="mob-pill w-14 h-8 rounded-full flex items-center justify-center">
            <svg class="icon-outline w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            <svg class="icon-solid w-5 h-5 fill-current" viewBox="0 0 20 20" aria-hidden="true"><path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"/><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"/></svg>
          </span>
          <span class="mob-label text-xs font-semibold leading-none">Opname</span>
        </a>

        <button type="button" onclick="window.openMobileSheet()" id="mob-nav-menu" aria-haspopup="dialog" aria-expanded="false"
                @if($isSecondaryModule) aria-current="page" @endif
                class="mob-nav-link flat-btn min-h-[54px] flex flex-col items-center justify-center gap-1" style="color: var(--wms-muted);">
          <span class="mob-pill w-14 h-8 rounded-full flex items-center justify-center">
            <svg class="icon-outline w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            <svg class="icon-solid w-5 h-5 fill-current" viewBox="0 0 20 20" aria-hidden="true"><path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
          </span>
          <span class="mob-label text-xs font-semibold leading-none">Modul</span>
        </button>
      </div>
    </nav>

    <!-- Accessible Mobile Bottom Sheet for Secondary Modules & Warehouse Switcher -->
    <div id="mobile-sheet-backdrop" data-open="false" class="flat-sheet-backdrop md:hidden" onclick="window.closeMobileSheet()"></div>
    <div id="mobile-sheet-panel" data-open="false" class="flat-sheet-panel md:hidden space-y-4" role="dialog" aria-modal="true" aria-labelledby="mobile-sheet-title">
      <div class="flex items-center justify-between gap-3 pb-3 border-b" style="border-color: var(--wms-divider);">
        <h2 id="mobile-sheet-title" class="text-base font-extrabold">Modul Operasional</h2>
        <button type="button" onclick="window.closeMobileSheet()" class="flat-btn flat-surface-alt min-h-[44px] px-3.5 rounded-lg text-xs font-bold">
          Tutup
        </button>
      </div>

      <div class="grid grid-cols-1 gap-2">
        <a href="{{ route('inbound') }}" class="flat-btn flat-surface-alt min-h-[50px] px-4 rounded-lg text-sm font-bold flex items-center justify-between">
          <span>Terima PO Supplier</span>
          <span class="font-code text-xs" style="color: var(--wms-muted);">PO</span>
        </a>
        <a href="{{ route('picking') }}" class="flat-btn flat-surface-alt min-h-[50px] px-4 rounded-lg text-sm font-bold flex items-center justify-between">
          <span>Outbound Picking &amp; Packing</span>
          <span class="font-code text-xs" style="color: var(--wms-muted);">ORD</span>
        </a>
        <a href="{{ route('transfers') }}" class="flat-btn flat-surface-alt min-h-[50px] px-4 rounded-lg text-sm font-bold flex items-center justify-between">
          <span>Mutasi Antar Gudang</span>
          <span class="font-code text-xs" style="color: var(--wms-muted);">TRF</span>
        </a>
      </div>

      @if(!empty($warehouses))
        <form method="POST" action="{{ route('warehouse.select') }}" class="pt-3 border-t space-y-2" style="border-color: var(--wms-divider);">
          @csrf
          <label for="mobile_warehouse_id" class="block text-xs font-bold" style="color: var(--wms-muted);">Gudang aktif</label>
          <select id="mobile_warehouse_id" name="warehouse_id" onchange="this.form.submit()" class="flat-input w-full min-h-[46px] rounded-lg px-3 py-2 text-sm font-bold">
            @foreach($warehouses as $wh)
              <option value="{{ $wh['id'] }}" @selected((int) $wh['id'] === (int) $activeWarehouseId)>{{ $wh['name'] }} ({{ strtoupper($wh['type'] ?? 'GUDANG') }})</option>
            @endforeach
          </select>
        </form>
      @endif

      <form method="POST" action="{{ route('logout') }}" class="pt-2">
        @csrf
        <button type="submit" class="flat-btn flat-surface-alt w-full min-h-[48px] rounded-lg text-sm font-extrabold" style="color: var(--wms-err-text);">
          Keluar dari {{ $user['name'] ?? 'Akun' }}
        </button>
      </form>
    </div>

    <!-- Live Camera Barcode Scanner Modal -->
    <div id="camera-scanner-modal" data-open="false" class="camera-modal-backdrop flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="camera-scanner-title">
      <div class="flat-surface w-full max-w-md rounded-2xl p-4 sm:p-5 space-y-4 border-2" style="border-color: var(--wms-divider);">
        <div class="flex items-start justify-between gap-2">
          <div class="min-w-0">
            <h2 id="camera-scanner-title" class="text-base font-extrabold">Pindai Barcode Kamera</h2>
            <p class="text-xs" style="color: var(--wms-muted);">Arahkan barcode produk ke dalam kotak bidik</p>
          </div>
          <div class="flex items-center gap-1.5 shrink-0">
            <button type="button" id="camera-torch-btn" onclick="window.toggleCameraTorch()" class="hidden flat-btn flat-surface-alt min-h-[40px] px-3 rounded-lg text-xs font-bold">Lampu</button>
            <button type="button" id="camera-switch-btn" onclick="window.switchCameraFacing()" class="hidden flat-btn flat-surface-alt min-h-[40px] px-3 rounded-lg text-xs font-bold">Balik</button>
            <button type="button" onclick="window.closeCameraScanner(true)" class="flat-btn flat-surface-alt min-h-[40px] px-3 rounded-lg text-xs font-extrabold">Tutup</button>
          </div>
        </div>

        <div class="relative w-full aspect-[4/3] bg-black rounded-xl overflow-hidden border" style="border-color: var(--wms-divider);">
          <video id="wms-camera-video" class="w-full h-full object-cover" autoplay playsinline muted></video>
          <div id="wms-camera-fallback-reader" class="w-full h-full hidden"></div>
          <div class="pointer-events-none absolute inset-0 flex items-center justify-center p-6">
            <div class="camera-reticle w-full max-w-[260px] h-36 rounded-xl relative">
              <div class="absolute inset-x-3 top-1/2 -translate-y-1/2 h-0.5" style="background-color: var(--wms-accent);"></div>
            </div>
          </div>
          <div id="wms-camera-status" class="absolute bottom-2.5 inset-x-3 text-center text-xs font-code font-bold px-3 py-1.5 rounded-lg bg-slate-950/85 text-slate-100">Menyiapkan kamera...</div>
        </div>

        <div class="flex items-center justify-between gap-2 pt-1">
          <span class="text-xs" style="color: var(--wms-muted);">Kamera sulit fokus?</span>
          <button type="button" onclick="window.closeCameraScanner(true)" class="flat-btn flat-surface-alt min-h-[42px] px-3.5 py-2 rounded-lg text-xs font-extrabold">Ketik Manual / Scanner Fisik</button>
        </div>
      </div>
    </div>
  @else
    <main class="flex-1">
      @yield('content')
    </main>
  @endif

  <script>
    window.audioCtx = null;
    window.playBeep = function (kind) {
      try {
        var AudioContext = window.AudioContext || window.webkitAudioContext;
        if (!AudioContext) return;
        if (!window.audioCtx) window.audioCtx = new AudioContext();
        if (window.audioCtx.state === 'suspended') window.audioCtx.resume();

        var osc = window.audioCtx.createOscillator();
        var gain = window.audioCtx.createGain();
        osc.type = kind === 'err' ? 'sawtooth' : 'sine';
        osc.frequency.setValueAtTime(kind === 'err' ? 240 : 1280, window.audioCtx.currentTime);
        gain.gain.setValueAtTime(0.08, window.audioCtx.currentTime);
        gain.gain.exponentialRampToValueAtTime(0.001, window.audioCtx.currentTime + (kind === 'err' ? 0.22 : 0.11));
        osc.connect(gain);
        gain.connect(window.audioCtx.destination);
        osc.start();
        osc.stop(window.audioCtx.currentTime + (kind === 'err' ? 0.23 : 0.12));
      } catch (e) {}
    };

    var toastTimer = null;
    window.showToast = function (message, kind) {
      var toast = document.getElementById('flat-toast');
      var msg = document.getElementById('toast-msg');
      var icon = document.getElementById('toast-icon');
      if (!toast || !msg) return;

      msg.textContent = message;
      if (kind === 'err') {
        toast.style.backgroundColor = 'var(--wms-err-bg)';
        toast.style.borderColor = 'var(--wms-err-border)';
        toast.style.color = 'var(--wms-err-text)';
        icon.textContent = '✕';
      } else {
        toast.style.backgroundColor = 'var(--wms-ok)';
        toast.style.borderColor = 'var(--wms-ok)';
        toast.style.color = 'var(--wms-ok-ink)';
        icon.textContent = '✓';
      }

      toast.setAttribute('data-open', 'true');
      clearTimeout(toastTimer);
      toastTimer = setTimeout(window.hideToast, 3800);
    };

    window.hideToast = function () {
      var toast = document.getElementById('flat-toast');
      if (toast) toast.setAttribute('data-open', 'false');
    };

    window.syncThemeButtons = function (theme) {
      document.querySelectorAll('[data-theme-btn]').forEach(function (btn) {
        btn.setAttribute('aria-pressed', btn.getAttribute('data-theme-btn') === theme ? 'true' : 'false');
      });
    };

    window.setTheme = function (theme) {
      document.documentElement.setAttribute('data-theme', theme);
      try { localStorage.setItem('wms_theme', theme); } catch (e) {}
      window.syncThemeButtons(theme);
    };

    window.toggleTheme = function () {
      var curr = document.documentElement.getAttribute('data-theme') || 'dark';
      window.setTheme(curr === 'dark' ? 'light' : 'dark');
    };

    window.WMS = {
      toggleTheme: window.toggleTheme,
      setTheme: window.setTheme
    };

    window.openMobileSheet = function () {
      var backdrop = document.getElementById('mobile-sheet-backdrop');
      var panel = document.getElementById('mobile-sheet-panel');
      var navBtn = document.getElementById('mob-nav-menu');
      var topBtn = document.getElementById('mob-top-menu-btn');
      if (backdrop) backdrop.setAttribute('data-open', 'true');
      if (panel) panel.setAttribute('data-open', 'true');
      if (navBtn) navBtn.setAttribute('aria-expanded', 'true');
      if (topBtn) topBtn.setAttribute('aria-expanded', 'true');
    };

    window.closeMobileSheet = function () {
      var backdrop = document.getElementById('mobile-sheet-backdrop');
      var panel = document.getElementById('mobile-sheet-panel');
      var navBtn = document.getElementById('mob-nav-menu');
      var topBtn = document.getElementById('mob-top-menu-btn');
      if (backdrop) backdrop.setAttribute('data-open', 'false');
      if (panel) panel.setAttribute('data-open', 'false');
      if (navBtn) navBtn.setAttribute('aria-expanded', 'false');
      if (topBtn) topBtn.setAttribute('aria-expanded', 'false');
    };

    window.wmsCameraState = {
      stream: null,
      scanTimer: null,
      detector: null,
      html5Qr: null,
      activeInput: null,
      facingMode: 'environment',
      videoInputs: [],
      torchOn: false,
      handled: false
    };

    window.setCameraButtonsVisible = function (visible) {
      document.querySelectorAll('[data-camera-scan-btn]').forEach(function (btn) {
        if (visible) {
          btn.classList.remove('hidden');
          btn.classList.add('inline-flex');
        } else {
          btn.classList.add('hidden');
          btn.classList.remove('inline-flex');
        }
      });
    };

    window.setCameraStatus = function (msg) {
      var el = document.getElementById('wms-camera-status');
      if (el) el.textContent = msg;
    };

    window.detectCameraSupport = function () {
      if (!navigator.mediaDevices || typeof navigator.mediaDevices.getUserMedia !== 'function') {
        window.setCameraButtonsVisible(false);
        return;
      }

      if (typeof navigator.mediaDevices.enumerateDevices !== 'function') {
        window.setCameraButtonsVisible(false);
        return;
      }

      navigator.mediaDevices.enumerateDevices().then(function (devices) {
        var videoInputs = (devices || []).filter(function (d) { return d.kind === 'videoinput'; });
        window.wmsCameraState.videoInputs = videoInputs;
        window.setCameraButtonsVisible(videoInputs.length > 0);

        var switchBtn = document.getElementById('camera-switch-btn');
        if (switchBtn) {
          switchBtn.classList.toggle('hidden', videoInputs.length <= 1);
        }
      }).catch(function () {
        window.setCameraButtonsVisible(false);
      });
    };

    window.stopCameraStream = function () {
      var st = window.wmsCameraState;
      if (st.scanTimer) {
        clearInterval(st.scanTimer);
        st.scanTimer = null;
      }
      if (st.stream) {
        st.stream.getTracks().forEach(function (track) {
          try { track.stop(); } catch (e) {}
        });
        st.stream = null;
      }
      if (st.html5Qr) {
        var qr = st.html5Qr;
        st.html5Qr = null;
        try {
          Promise.resolve(qr.stop()).then(function () {
            try { qr.clear(); } catch (e) {}
          }).catch(function () {});
        } catch (e) {}
      }
      var videoEl = document.getElementById('wms-camera-video');
      if (videoEl) {
        videoEl.srcObject = null;
        videoEl.classList.remove('hidden');
      }
      var fallbackEl = document.getElementById('wms-camera-fallback-reader');
      if (fallbackEl) {
        fallbackEl.classList.add('hidden');
        fallbackEl.innerHTML = '';
      }
      var torchBtn = document.getElementById('camera-torch-btn');
      if (torchBtn) torchBtn.classList.add('hidden');
      st.torchOn = false;
    };

    window.openCameraScanner = function (triggerBtn) {
      var container = triggerBtn && (triggerBtn.closest('form') || triggerBtn.parentElement);
      var targetInput = (container && container.querySelector('[data-scan-input]')) || document.querySelector('[data-scan-input]');
      window.wmsCameraState.activeInput = targetInput;
      window.wmsCameraState.handled = false;

      var modal = document.getElementById('camera-scanner-modal');
      if (modal) modal.setAttribute('data-open', 'true');
      window.setCameraStatus('Menyiapkan kamera...');
      window.startCameraStream();
    };

    window.closeCameraScanner = function (focusInput) {
      window.stopCameraStream();
      var modal = document.getElementById('camera-scanner-modal');
      if (modal) modal.setAttribute('data-open', 'false');
      if (focusInput && window.wmsCameraState.activeInput) {
        window.wmsCameraState.activeInput.focus();
      }
    };

    window.handleCameraBarcodeDetected = function (rawCode) {
      var code = (rawCode || '').trim();
      if (!code || window.wmsCameraState.handled) return;
      window.wmsCameraState.handled = true;

      var targetInput = window.wmsCameraState.activeInput || document.querySelector('[data-scan-input]');
      window.playBeep('ok');
      try { if (navigator.vibrate) navigator.vibrate(60); } catch (e) {}
      window.closeCameraScanner(false);

      if (!targetInput) return;
      targetInput.value = code;

      if (targetInput.getAttribute('data-scan-mode') === 'inbound-sku' || targetInput.id === 'inbound-quick-sku') {
        if (typeof window.highlightInboundSku === 'function') {
          window.highlightInboundSku(code);
        }
        return;
      }

      if (targetInput.form) {
        if (typeof targetInput.form.requestSubmit === 'function') {
          targetInput.form.requestSubmit();
        } else {
          targetInput.form.submit();
        }
      }
    };

    window.handleCameraError = function (err) {
      var name = (err && err.name) || '';
      if (name === 'NotFoundError' || name === 'DevicesNotFoundError') {
        window.setCameraButtonsVisible(false);
        window.closeCameraScanner(true);
        window.showToast('Perangkat ini tidak memiliki kamera. Gunakan scanner fisik atau ketik manual.', 'err');
        return;
      }
      if (name === 'NotAllowedError' || name === 'PermissionDeniedError') {
        window.setCameraStatus('Izin kamera ditolak. Berikan izin kamera di browser atau gunakan input manual.');
        window.showToast('Izin kamera ditolak.', 'err');
        return;
      }
      window.setCameraStatus('Kamera tidak dapat diakses. Gunakan scanner fisik atau ketik manual.');
    };

    window.loadHtml5QrcodeFallback = function () {
      return new Promise(function (resolve, reject) {
        if (window.Html5Qrcode) {
          resolve(window.Html5Qrcode);
          return;
        }
        var script = document.createElement('script');
        script.src = 'https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js';
        script.async = true;
        script.onload = function () {
          if (window.Html5Qrcode) resolve(window.Html5Qrcode);
          else reject(new Error('Html5Qrcode unavailable'));
        };
        script.onerror = function () {
          reject(new Error('Failed to load Html5Qrcode'));
        };
        document.head.appendChild(script);
      });
    };

    window.startCameraStream = function () {
      window.stopCameraStream();
      var st = window.wmsCameraState;

      if (!navigator.mediaDevices || typeof navigator.mediaDevices.getUserMedia !== 'function') {
        window.handleCameraError({ name: 'NotFoundError' });
        return;
      }

      var videoEl = document.getElementById('wms-camera-video');
      var fallbackEl = document.getElementById('wms-camera-fallback-reader');

      if ('BarcodeDetector' in window) {
        navigator.mediaDevices.getUserMedia({
          video: { facingMode: { ideal: st.facingMode }, width: { ideal: 1280 }, height: { ideal: 720 } },
          audio: false
        }).then(function (stream) {
          st.stream = stream;
          if (videoEl) {
            videoEl.classList.remove('hidden');
            videoEl.srcObject = stream;
            videoEl.play().catch(function () {});
          }
          if (fallbackEl) fallbackEl.classList.add('hidden');

          var track = stream.getVideoTracks()[0];
          var torchBtn = document.getElementById('camera-torch-btn');
          if (track && typeof track.getCapabilities === 'function' && torchBtn) {
            var caps = track.getCapabilities();
            torchBtn.classList.toggle('hidden', !caps || !caps.torch);
          }

          if (!st.detector) {
            try {
              st.detector = new window.BarcodeDetector({
                formats: ['ean_13', 'ean_8', 'upc_a', 'upc_e', 'code_128', 'code_39', 'code_93', 'itf', 'qr_code', 'data_matrix']
              });
            } catch (e) {
              st.detector = new window.BarcodeDetector();
            }
          }

          window.setCameraStatus('Arahkan barcode ke kotak bidik...');
          st.scanTimer = setInterval(function () {
            if (!videoEl || videoEl.readyState < 2 || st.handled) return;
            st.detector.detect(videoEl).then(function (barcodes) {
              if (barcodes && barcodes.length > 0 && barcodes[0].rawValue) {
                window.handleCameraBarcodeDetected(barcodes[0].rawValue);
              }
            }).catch(function () {});
          }, 140);
        }).catch(window.handleCameraError);
        return;
      }

      if (videoEl) videoEl.classList.add('hidden');
      if (fallbackEl) fallbackEl.classList.remove('hidden');
      window.setCameraStatus('Memuat pemindai kamera...');

      window.loadHtml5QrcodeFallback().then(function (Html5Qrcode) {
        var qr = new Html5Qrcode('wms-camera-fallback-reader');
        st.html5Qr = qr;
        return qr.start(
          { facingMode: st.facingMode },
          { fps: 10, qrbox: { width: 240, height: 140 } },
          function (decodedText) {
            window.handleCameraBarcodeDetected(decodedText);
          },
          function () {}
        ).then(function () {
          window.setCameraStatus('Arahkan barcode ke kotak bidik...');
        });
      }).catch(window.handleCameraError);
    };

    window.switchCameraFacing = function () {
      window.wmsCameraState.facingMode = window.wmsCameraState.facingMode === 'environment' ? 'user' : 'environment';
      window.startCameraStream();
    };

    window.toggleCameraTorch = function () {
      var st = window.wmsCameraState;
      if (!st.stream) return;
      var track = st.stream.getVideoTracks()[0];
      if (!track || typeof track.applyConstraints !== 'function') return;
      var next = !st.torchOn;
      track.applyConstraints({ advanced: [{ torch: next }] }).then(function () {
        st.torchOn = next;
      }).catch(function () {});
    };

    document.addEventListener('DOMContentLoaded', function () {
      window.syncThemeButtons(document.documentElement.getAttribute('data-theme') || 'dark');
      window.detectCameraSupport();
      if (navigator.mediaDevices && typeof navigator.mediaDevices.addEventListener === 'function') {
        navigator.mediaDevices.addEventListener('devicechange', window.detectCameraSupport);
      }

      @if(session('success'))
        window.playBeep('ok');
        window.showToast(@json(session('success')), 'ok');
      @elseif($errors->any())
        window.playBeep('err');
        window.showToast(@json($errors->first()), 'err');
      @endif
    });

    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape') {
        var camModal = document.getElementById('camera-scanner-modal');
        if (camModal && camModal.getAttribute('data-open') === 'true') {
          window.closeCameraScanner(true);
          return;
        }
        var panel = document.getElementById('mobile-sheet-panel');
        if (panel && panel.getAttribute('data-open') === 'true') {
          window.closeMobileSheet();
          return;
        }
      }

      if ((e.key === '/' || (e.ctrlKey && e.key.toLowerCase() === 'k')) && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
        var scanInput = document.querySelector('[data-scan-input]');
        if (scanInput) {
          e.preventDefault();
          scanInput.focus();
          scanInput.select();
        }
      }
    });
  </script>
  @stack('scripts')
</body>
</html>
