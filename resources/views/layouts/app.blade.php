<!DOCTYPE html>
<html lang="id" data-theme="dark">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
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
      left: 16px;
      right: 16px;
      max-width: 460px;
      margin-left: auto;
      margin-right: auto;
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

    .rail-nav-link[aria-current="page"],
    .tab-nav-link[aria-current="page"],
    [data-theme-btn][aria-pressed="true"] {
      background-color: var(--wms-accent);
      color: var(--wms-accent-ink);
      border-color: var(--wms-accent);
    }

    .mobile-content-pad { padding-bottom: calc(5.75rem + env(safe-area-inset-bottom)); }
    @media (min-width: 768px) { .mobile-content-pad { padding-bottom: 2.5rem; } }
    .mobile-bottom-nav { padding-bottom: max(0.5rem, env(safe-area-inset-bottom)); }
  </style>
</head>
<body class="min-h-screen flex flex-col">

  <!-- Interruptible Toast -->
  <div id="flat-toast" class="flat-toast rounded-lg px-4 py-3 border-2 flex items-center gap-3" role="status" aria-live="polite" data-open="false">
    <span id="toast-icon" class="shrink-0 font-bold text-base">●</span>
    <span id="toast-msg" class="text-sm font-bold flex-1 leading-snug">Notifikasi</span>
    <button type="button" onclick="window.hideToast()" class="flat-btn px-2 py-1 text-xs font-extrabold uppercase tracking-wider rounded opacity-80 hover:opacity-100">Tutup</button>
  </div>

  @if(session()->has('api_token'))
    @php
      $user = session('user', []);
      $warehouses = session('warehouses', []);
      $activeWarehouseId = session('active_warehouse_id');
      $activeWarehouse = collect($warehouses)->first(fn ($w) => (int) ($w['id'] ?? 0) === (int) $activeWarehouseId);
      $activeWarehouseName = $activeWarehouse['name'] ?? 'Semua Gudang';

      $navItems = [
        ['route' => 'dashboard', 'label' => 'Beranda', 'short' => 'Home', 'pattern' => 'dashboard'],
        ['route' => 'scanner', 'label' => 'Cek Barcode', 'short' => 'Scan', 'pattern' => 'scanner'],
        ['route' => 'opname', 'label' => 'Stock Opname', 'short' => 'Opname', 'pattern' => 'opname*'],
        ['route' => 'inbound', 'label' => 'Terima PO', 'short' => 'Inbound', 'pattern' => 'inbound*'],
        ['route' => 'picking', 'label' => 'Picking & Packing', 'short' => 'Picking', 'pattern' => 'picking*'],
        ['route' => 'transfers', 'label' => 'Mutasi Gudang', 'short' => 'Mutasi', 'pattern' => 'transfers*'],
      ];
    @endphp

    <!-- Mobile & Tablet Compact Header Bar -->
    <header class="lg:hidden sticky top-0 z-40 border-b px-4 py-3 flex items-center justify-between gap-2" style="background-color: var(--wms-surface); border-color: var(--wms-divider);">
      <div class="flex items-center gap-2.5 min-w-0">
        <div class="w-9 h-9 rounded-lg flex items-center justify-center font-extrabold text-sm shrink-0" style="background-color: var(--wms-accent); color: var(--wms-accent-ink);">G</div>
        <div class="min-w-0">
          <div class="text-sm font-extrabold tracking-tight truncate">Grosera WMS</div>
          <div class="text-[11px] font-semibold truncate" style="color: var(--wms-muted);">{{ $activeWarehouseName }} · {{ $user['name'] ?? 'Staf Gudang' }}</div>
        </div>
      </div>

      <div class="flex items-center gap-1.5 shrink-0">
        @if(!empty($warehouses))
          <form method="POST" action="{{ route('warehouse.select') }}">
            @csrf
            <select name="warehouse_id" onchange="this.form.submit()" aria-label="Pilih Gudang Aktif" class="flat-input rounded-lg px-2 py-1.5 text-xs font-bold max-w-[130px] truncate">
              @foreach($warehouses as $wh)
                <option value="{{ $wh['id'] }}" @selected((int) $wh['id'] === (int) $activeWarehouseId)>{{ $wh['name'] }}</option>
              @endforeach
            </select>
          </form>
        @endif
        <button type="button" onclick="window.toggleTheme()" class="flat-btn flat-surface-alt px-2.5 py-2 rounded-lg text-xs font-extrabold" aria-label="Ubah Tema">◐</button>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button type="submit" class="flat-btn flat-surface-alt px-2.5 py-2 rounded-lg text-xs font-bold" style="color: var(--wms-err-text);">Keluar</button>
        </form>
      </div>
    </header>

    <!-- Tablet Horizontal Tab Strip (md only) -->
    <nav class="hidden md:flex lg:hidden border-b px-4 py-2 gap-1.5 overflow-x-auto" style="background-color: var(--wms-surface); border-color: var(--wms-divider);" aria-label="Navigasi Modul Tablet">
      @foreach($navItems as $item)
        <a href="{{ route($item['route']) }}"
           @if(request()->routeIs($item['pattern'])) aria-current="page" @endif
           class="tab-nav-link flat-btn flat-surface-alt px-3.5 py-2 rounded-lg text-xs font-bold whitespace-nowrap">
          {{ $item['label'] }}
        </a>
      @endforeach
    </nav>

    <div class="flex-1 flex flex-col lg:flex-row">
      <!-- Desktop Left Navigation Rail (lg+) -->
      <aside class="hidden lg:flex lg:flex-col lg:w-64 lg:shrink-0 border-r min-h-screen sticky top-0 h-screen" style="background-color: var(--wms-surface); border-color: var(--wms-divider);">
        <div class="p-4 border-b flex items-center gap-3" style="border-color: var(--wms-divider);">
          <div class="w-10 h-10 rounded-lg flex items-center justify-center font-extrabold text-base shrink-0" style="background-color: var(--wms-accent); color: var(--wms-accent-ink);">G</div>
          <div class="min-w-0">
            <div class="text-sm font-extrabold tracking-tight">Grosera WMS</div>
            <div class="text-xs font-semibold truncate" style="color: var(--wms-muted);">Operasional Gudang</div>
          </div>
        </div>

        <div class="p-4 border-b space-y-2" style="border-color: var(--wms-divider);">
          <div class="text-[11px] font-bold uppercase tracking-wider" style="color: var(--wms-muted);">Gudang Aktif</div>
          @if(!empty($warehouses))
            <form method="POST" action="{{ route('warehouse.select') }}">
              @csrf
              <select name="warehouse_id" onchange="this.form.submit()" aria-label="Pilih Gudang Aktif" class="flat-input w-full rounded-lg px-3 py-2 text-xs font-bold">
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
               class="rail-nav-link flat-btn flat-hover-surface flex items-center justify-between px-3 py-2.5 rounded-lg text-sm font-bold border border-transparent">
              <span>{{ $item['label'] }}</span>
              <span class="font-code text-[11px] opacity-70">{{ $index + 1 }}</span>
            </a>
          @endforeach
        </nav>

        <div class="p-4 border-t space-y-3" style="border-color: var(--wms-divider);">
          <div class="flex items-center justify-between gap-2">
            <span class="text-xs font-bold" style="color: var(--wms-muted);">Tema Layar</span>
            <div class="flex items-center gap-1">
              <button type="button" data-theme-btn="dark" onclick="window.setTheme('dark')" aria-pressed="true" class="flat-btn flat-surface-alt px-2.5 py-1 rounded text-[11px] font-bold border">Gelap</button>
              <button type="button" data-theme-btn="light" onclick="window.setTheme('light')" aria-pressed="false" class="flat-btn flat-surface-alt px-2.5 py-1 rounded text-[11px] font-bold border">Terang</button>
            </div>
          </div>

          <div class="pt-2 border-t flex items-center justify-between gap-2" style="border-color: var(--wms-divider);">
            <div class="min-w-0">
              <div class="text-xs font-extrabold truncate">{{ $user['name'] ?? 'Staf Gudang' }}</div>
              <div class="text-[11px] truncate" style="color: var(--wms-muted);">{{ $user['email'] ?? '' }}</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
              @csrf
              <button type="submit" class="flat-btn flat-surface-alt px-3 py-1.5 rounded-lg text-xs font-extrabold" style="color: var(--wms-err-text);">Keluar</button>
            </form>
          </div>
        </div>
      </aside>

      <!-- Main Operational Workspace -->
      <main class="flex-1 min-w-0 px-4 sm:px-6 lg:px-8 py-5 mobile-content-pad max-w-7xl w-full mx-auto space-y-5">
        @if(session('success'))
          <div class="rounded-lg border-2 px-4 py-3 text-sm font-bold flex items-start gap-2.5" style="background-color: rgba(16, 185, 129, 0.14); border-color: var(--wms-ok); color: var(--wms-ok-text);">
            <span>✓</span>
            <span>{{ session('success') }}</span>
          </div>
        @endif

        @if($errors->any())
          <div class="rounded-lg border-2 px-4 py-3 text-sm font-bold space-y-1" style="background-color: var(--wms-err-bg); border-color: var(--wms-err-border); color: var(--wms-err-text);">
            @foreach($errors->all() as $err)
              <div>✕ {{ $err }}</div>
            @endforeach
          </div>
        @endif

        @yield('content')
      </main>
    </div>

    <!-- Mobile Bottom Capsule Nav -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 border-t px-1.5 pt-1.5 mobile-bottom-nav grid grid-cols-6 gap-0.5" style="background-color: var(--wms-surface); border-color: var(--wms-divider);" aria-label="Navigasi Bawah Gudang">
      @foreach($navItems as $item)
        <a href="{{ route($item['route']) }}"
           @if(request()->routeIs($item['pattern'])) aria-current="page" @endif
           class="mob-nav-link flex flex-col items-center justify-center gap-0.5 py-1 text-center">
          <span class="mob-pill px-2 py-1 rounded-full text-[10px] font-extrabold truncate max-w-full">{{ $item['short'] }}</span>
        </a>
      @endforeach
    </nav>
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

    document.addEventListener('DOMContentLoaded', function () {
      window.syncThemeButtons(document.documentElement.getAttribute('data-theme') || 'dark');

      @if(session('success'))
        window.playBeep('ok');
        window.showToast(@json(session('success')), 'ok');
      @elseif($errors->any())
        window.playBeep('err');
        window.showToast(@json($errors->first()), 'err');
      @endif
    });

    document.addEventListener('keydown', function (e) {
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
